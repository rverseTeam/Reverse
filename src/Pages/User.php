<?php
/**
 * Holds the profile page.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\User as Profile;

/**
 * Profile page.
 * @package Miiverse
 * @author Repflez
 */
class User extends Page
{
	/**
	 * Serves the profile index.
	 * @return string
	 */
	public function profile(string $name) : string {
		$profile = Profile::construct($name);

		if (!$profile) {
			return view('errors/404');
		}

		return view('user/profile', compact('profile'));
	}
}
