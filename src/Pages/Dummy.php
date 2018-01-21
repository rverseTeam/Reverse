<?php
/**
 * Holds a dummy page.
 * @package Miiverse
 */

namespace Miiverse\Pages;

/**
 * Dummy page.
 * @package Miiverse
 * @author Repflez
 */
class Dummy extends Page
{
	/**
	 * Serves an empty page, for thosse cases it's needed.
	 * @return string
	 */
	public function dummy() : string {
		return '';
	}
}
