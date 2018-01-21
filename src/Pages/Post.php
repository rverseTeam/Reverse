<?php
/**
 * Holds the post handler.
 * @package Miiverse
 */

namespace Miiverse\Pages;

use Miiverse\DB;

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
					'spoiler' => intval($spoiler),
				]);

				redirect(route('post.show', ['id' => hashid($id), 'pid' => hashid($postId)]));
				break;
			default:
				break;
		}
		return '';
	}

	/**
	 * Shows an individual post
	 */
	public function show(string $id, string $pid) : string {
		return '';
	}
}
