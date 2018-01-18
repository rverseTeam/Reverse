<?php
/**
 * Holds the home page.
 * @package Miiverse
 */

namespace Miiverse\Pages\Titles;

/**
 * Home page.
 * @package Miiverse
 * @author Repflez
 */
class Show extends Page
{
	/**
	 * Serves the site index.
	 * @return string
	 */
	public function init() : string {
		var_dump($_REQUEST);
		return '';
	}
}
