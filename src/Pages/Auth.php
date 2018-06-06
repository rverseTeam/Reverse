<?php
/**
 * Holds the auth controllers.
 */

namespace Miiverse\Pages;

use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Miiverse\CurrentSession;
use Miiverse\DB;
use Miiverse\Net;
use Miiverse\User;

/**
 * Authentication controllers.
 *
 * @author Repflez
 */
class Auth extends Page
{
	/**
	 * Touch the login rate limit.
	 * @param int $user The ID of the user that attempted to log in.
	 * @param bool $success Whether the login attempt was successful.
	 */
	protected function touchRateLimit(int $user, bool $success = false) : void {
		DB::table('login_attempts')->insert([
				'attempt_success' => $success ? 1 : 0,
				'attempt_timestamp' => time(),
				'attempt_ip' => Net::pton(Net::ip()),
				'user_id' => $user,
			]);
	}

	protected function authenticate(User $user) : void {
		// Generate a session key
		$session = CurrentSession::create(
			$user->id,
			Net::ip(),
			get_country_code()
		);

		$cookiePrefix = config('cookie.prefix');
		setcookie("{$cookiePrefix}id", $user->id, time() + 604800, '/');
		setcookie("{$cookiePrefix}session", $session->key, time() + 604800, '/');

		$this->touchRateLimit($user->id, true);
	}

	/**
	 * End the current session.
	 * @throws HttpMethodNotAllowedException
	 */
	public function logout() {
		if (!session_check('z')) {
			throw new HttpMethodNotAllowedException;
		}

		// Destroy the active session
		CurrentSession::stop();

		return redirect(route('main.index'));
	}

	/**
	 * Login page.
	 * @return string
	 */
	public function login() : string {
		if (!validateToken('login', 'post', false)) {
			return redirect(route('auth.loginform') . '?mes=' . urlencode(__('auth.errors.invalid_token')));
		}

		// Get request variables
		$username = $_POST['username'] ?? null;
		$password = $_POST['password'] ?? null;

		// Check if we haven't hit the rate limit
		$rates = DB::table('login_attempts')->where('attempt_ip', Net::pton(Net::ip()))->where('attempt_timestamp', '>', time() - 1800)->where('attempt_success', '0')->count();

		if ($rates > 4) {
			return redirect(route('auth.loginform') . '?mes=' . urlencode(__('auth.errors.too_many_attempts')));
		}

		$user = User::construct(clean_string($username, true));

		// Check if the user that's trying to log in actually exists
		if ($user->id === 0) {
			$this->touchRateLimit($user->id);
			return redirect(route('auth.loginform') . '?mes=' . urlencode(__('auth.errors.invalid_details')));
		}

		if ($user->passwordExpired()) {
			return redirect(route('auth.loginform') . '?mes=' . urlencode(__('auth.errors.password_expired')));
		}

		if (!$user->verifyPassword($password)) {
			$this->touchRateLimit($user->id);
			return redirect(route('auth.loginform') . '?mes=' . __('auth.errors.invalid_details'));
		}

		$this->authenticate($user);

		return redirect(route('main.index'));
	}

	/**
	 * Serve the login form
	 * @return string
	 */
	public function login_form() : string {
		if (CurrentSession::$user->id !== 0) {
			return redirect(route('main.index'));
		}

		return view('members/login');
	}
}
