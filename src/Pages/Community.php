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
		$communities = [
			'general' => DB::table('communities')
							->where('type', '=', 0)
							->latest('created')
							->limit(6)
							->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
			'game' => DB::table('communities')
						->where([
							['type', '>', 0],
							['type', '<', 4],
						])
						->latest('created')
						->limit(6)
						->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
			'special' => DB::table('communities')
							->where('type', '=', 4)
							->latest('created')
							->limit(6)
							->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
		];

		return view('community/index', compact('communities'));
	}
}