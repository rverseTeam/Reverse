<?php
/**
 * Holds the user gate.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\Upload;
use Miiverse\User;
use Miiverse\Helpers\ConsoleAuth;
use Miiverse\Helpers\Mii;

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
		// Create the account
		$user = User::create($_POST['welcome_username'], $_POST['welcome_nnid']);

		// Save the console ID to the linked account table
		DB::table('console_auth')->insert([
			'user_id' => $user->id,
			'short_id' => ConsoleAuth::$consoleId->short,
			'long_id' => ConsoleAuth::$consoleId->long,
		]);

		// Get all Mii images and save them to the mapping table
		$id = Mii::get($user->nnid);

		$miis_temp = Mii::getMiiImages($id);

		$miis = [];

		foreach ($miis_temp as $name => $mii) {
			$miis[$name] = Upload::uploadMii($mii);
		}

		DB::table('mii_mappings')->insert([
			'user_id' => $user->id,
			'normal' => $miis['normal_face'],
			'like' => $miis['like_face'],
			'happy' => $miis['happy_face'],
			'frustrated' => $miis['frustrated_face'],
			'puzzled' => $miis['puzzled_face'],
			'surprised' => $miis['surprised_face'],
		]);

		return '';
	}

	/**
	 * Checks user supplied data on the database
	 * @return string
	 */
	public function check() : string {
		$username = clean_string($_POST['welcome_username']);
		$nnid = str_replace(' ', '_', $_POST['welcome_nnid']);

		$user = DB::table('users')->where([
			'username' => $username,
		])->first();

		if (!$user) {
			$user = DB::table('users')->where([
				'nnid' => $nnid,
			])->first();

			if (!$user) {
				$mii = Mii::check($nnid);

				if (!$mii)
					return 'nonnid';
				else
					return 'ok';
			} else {
				return 'nnid';
			}
		} else {
			return 'username';
		}
	}
}
