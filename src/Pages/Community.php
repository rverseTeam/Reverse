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
	 * Community index.
	 * @return string
	 */
	public function index() : string {
		// Fetch the last 10 communities
		$communities = DB::table('communities')
			->where('type', '=', 0)
			->latest('created')
			->limit(10)
			->get(['id', 'title_id', 'name', 'icon', 'type']);

		return view('community/index', compact('communities'));
	}
}