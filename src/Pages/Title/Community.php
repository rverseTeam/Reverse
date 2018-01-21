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
	public function show($tid, $id) : string {
		$community = dehashid($id);
		$titileId = dehashid($tid);

		if (!is_array($community) || !is_array($titileId)) {
			return view('errors/404');
		}

		$meta = DB::table('communities')
					->where('id', $community)
					->first();

		if (!$meta) {
			return view('errors/404');
		}

		$posts = DB::table('posts')
					->where('community', $community)
					->limit(10)
					->get(['id', 'user_id', 'created', 'edited', 'deleted', 'content', 'image', 'feeling']);

		return view('titles/view', compact('meta', 'posts'));
	}

	/**
	 * Post form for communities.
	 * @return string
	 */
	public function post($tid, $id) : string {
		$community = dehashid($id);
		$titileId = dehashid($tid);

		if (!is_array($community) || !is_array($titileId)) {
			return view('errors/404');
		}

		$meta = DB::table('communities')
					->where('id', $community)
					->first();

		if (!$meta) {
			return view('errors/404');
		}

		return view('titles/post', compact('meta'));
	}
}