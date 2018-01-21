<?php
/**
 * Holds the community for a specific title.
 * @package Miiverse
 */

namespace Miiverse\Pages\Title;

use Miiverse\DB;
use Miiverse\User;

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
		$posts = [];

		if (!is_array($community) || !is_array($titileId)) {
			return view('errors/404');
		}

		$meta = DB::table('communities')
					->where('id', $community)
					->first();

		if (!$meta) {
			return view('errors/404');
		}

		$posts_pre = DB::table('posts')
					->where('community', $community)
					->limit(10)
					->get(['id', 'user_id', 'created', 'edited', 'deleted', 'content', 'image', 'feeling', 'spoiler']);

		foreach ($posts_pre as $post) {
			$posts[] = [
				'id' => hashid($post->id),
				'user' => User::construct($post->user_id),
				'created' => $post->created,
				'content' => $post->content,
				'image' => $post->image,
				'feeling' => $post->feeling,
				'spoiler' => $post->spoiler
			];
		}

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