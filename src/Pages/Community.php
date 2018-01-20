<?php
/**
 * Holds the community pages.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\DB;

/**
 * Community page.
 * @package Miiverse
 * @author Repflez
 */
class Community extends Page
{
	/**
	 * Community index
	 * @return string
	 */
	public function index() : string {
		return view('community/index');
	}
}