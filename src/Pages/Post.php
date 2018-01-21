<?php
/**
 * Holds the post handler.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\CurrentSession;
use Miiverse\DB;
use Miiverse\User;
use Miiverse\Community\Community;

/**
 * Post handler.
 * @package Miiverse
 * @author Repflez
 */
class Post extends Page
{
	/**
	 * Creates a post.
	 */
	public function create() {
		$title_id = $_POST["olive_title_id"];
		$id = $_POST["olive_community_id"];
		$feeling = $_POST["feeling_id"];
		$spoiler = $_POST['is_spoiler'] ?? 0;
		$type = $_POST["_post_type"];

		switch ($type) {
			case 'body':
				$body = $_POST["body"];

				$postId = DB::table('posts')->insertGetId([
					'community' => $id,
					'content' => $body,
					'feeling' => $feeling,
					'user_id' => CurrentSession::$user->id,
					'spoiler' => intval($spoiler),
				]);

				redirect(route('post.show', ['id' => hashid($postId)]));
				break;
			case 'painting':
				$painting = base64_decode($_POST["painting"]);
				$painting_name = CurrentSession::$user->id . '-' . time() . '.png';

				file_put_contents(path('public/img/drawings/' . $painting_name), $painting);

				$postId = DB::table('posts')->insertGetId([
					'community' => $id,
					'image' => $painting_name,
					'feeling' => $feeling,
					'user_id' => CurrentSession::$user->id,
					'spoiler' => intval($spoiler),
				]);

				redirect(route('post.show', ['id' => hashid($postId)]));
				break;
			default:
				break;
		}
		return '';
	}

	/**
	 * Shows an individual post
	 */
	public function show(string $id) : string {
		$post_id = dehashid($id);

		$post_meta = DB::table('posts')
						->where('id', $post_id)
						->first();

		$meta = new Community($post_meta->community);
		$creator = User::construct($post_meta->user_id);

		return view('posts/view', compact('post_meta', 'meta', 'creator'));
	}
}
