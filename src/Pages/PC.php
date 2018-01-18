<?php
/**
 * Holds the home page.
 * @package Miiverse
 */

namespace Miiverse\Pages;

/**
 * Home page.
 * @package Miiverse
 * @author Repflez
 */
class PC extends Page
{
	/**
	 * Serves the site index.
	 * @return string
	 */
	public function warning() : string {
		return view('errors/teaser');
	}
}
