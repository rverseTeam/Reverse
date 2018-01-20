<?php
/**
 * Holds the user gate.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\Net;

/**
 * User gate.
 * @package Miiverse
 * @author Repflez
 */
class Gate extends Page
{
	/**
	 * Serves the user welcome.
	 * @return string
	 */
	public function welcome() : string {
		return view('gate/welcome');
	}

	/**
	 * Activates an account
	 * @return string
	 */
	public function activate() {
		return '';
	}

	/**
	 * Checks user supplied data on the database
	 * @return string
	 */
	public function check() : string {
		$username = $_POST['welcome_username'];
		$nnid = $_POST['welcome_nnid'];

		$user = DB::table('users')->where([
			'username' => $username,
		])->first();

		if (!$user) {
			$user = DB::table('users')->where([
				'nnid' => $nnid,
			])->first();

			if (!$user)
				return 'ok';
			else
				return 'nnid';
		} else {
			return 'username';
		}
	}
}
