<?php
/**
 * Holds information about the currently active session
 * @package Miiverse
 */

namespace Miiverse;

/**
 * Information about the current active user and session.
 * @package Miiverse
 * @author Repflez
 */
class CurrentSession
{
	/**
	 * The user object of the currently active user.
	 * @var User
	 */
	public static $user = null;

	/**
	 * The currently active session object.
	 * @var Session
	 */
	public static $session = null;

	/**
	 * Prepare the current session backend.
	 * @param int $user
	 * @param string $session
	 * @param string $ip
	 */
	public static function start(int $user, string $session, string $ip) : void {
		// Check if a PHP session was already started and if not start one
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		// Create a session object
		self::$session = new Session($session);

		// Create a user object
		$user = User::construct($user);

		// Check if the session exists and check if the user is activated
		if (self::$session->validate($user->id, $ip)
			&& $user->activated) {
			// Assign the user object
			self::$user = $user;
		} else {
			self::$user = User::construct(0);
		}
	}

	/**
	 * Stop the current session
	 */
	public static function stop() : void {
		self::$session->delete();
		session_regenerate_id(true);
		session_destroy();
	}

	/**
	 * Create a new Miiverse session.
	 * @param int $user
	 * @param string $ip
	 * @param string $country
	 * @param bool $remember
	 * @param int $length
	 * @return Session
	 */
	public static function create(int $user, string $ip, string $country, int $length = 604800) {
		return Session::create($user, $ip, $country, $length);
	}

	/**
	 * Auth a user based on their console info.
	 * @param array $console
	 * @return void
	 */
	public static function authByConsole(array $console) : void {
		$user = DB::table('console_auth')->where([
				'friend_pid' => $console['friendPID'],
			])->first();

		if ($user) {
			$session = CurrentSession::create(
					$user->user_id,
					Net::ip(),
					get_country_code(),
					clean_string($_SERVER['HTTP_USER_AGENT'] ?? '')
				);

			self::start(
				$user->user_id,
				$session->session_key,
				Net::ip()
			);
		}
	}
}
