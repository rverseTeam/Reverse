<?php
/**
 * Holds the community for a specific title.
 * @package Miiverse
 */

namespace Miiverse\Pages\Title;

use Miiverse\DB;

/**
 * Community page for rtitles.
 * @package Miiverse
 * @author Repflez
 */
class Community extends Page
{
	/**
	 * Title community index.
	 * @return string
	 */
	public function show($tid = '', $id = '') : string {
		return '<pre>' . var_dump($tid, $id);
	}
}