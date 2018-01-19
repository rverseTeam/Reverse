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
	 * Activates an account (maybe?)
	 */
	public function activate() {
		Net::request('', 'POST', $_POST);
	}
}
