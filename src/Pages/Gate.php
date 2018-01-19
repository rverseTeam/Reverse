<?php
/**
 * Holds the user gate.
 * @package Miiverse
 */

namespace Miiverse\Pages;

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
}
