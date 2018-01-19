<?php
/**
 * Holds the home page.
 * @package Miiverse
 */

namespace Miiverse\Pages\Titles;

use Miiverse\Helpers\ParamPack;

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
		return view('titles/show');
	}
}
