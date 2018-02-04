<?php
/**
 * Holds the community for a specific title.
 */

namespace Miiverse\Pages\Title;

use Miiverse\CurrentSession;
use Miiverse\DB;
use Miiverse\User;

/**
 * Community page for rtitles.
 *
 * @author Repflez
 */
class Community extends Page
{
    /**
     * Title community index.
     *
     * @return string
     */
    public function show($tid, $id) : string
    {
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
                    ->orderBy('created', 'desc')
                    ->limit(10)
                    ->get();

        foreach ($posts_pre as $post) {
            $posts[] = [
                'id'       => hashid($post->id),
                'user'     => User::construct($post->user_id),
                'created'  => $post->created,
                'content'  => $post->content,
                'image'    => $post->image,
                'feeling'  => intval($post->feeling),
                'spoiler'  => $post->spoiler,
                'comments' => intval($post->comments),
                'likes'    => intval($post->likes),
                'liked'    => (bool) DB::table('likes')
                                    ->where([
                                        ['type', 0], // Posts are type 0
                                        ['id', $post->id],
                                        ['user', CurrentSession::$user->id],
                                    ])
                                    ->count(),
            ];
        }

        $feeling = ['normal', 'happy', 'like', 'surprised', 'frustrated', 'puzzled'];
        $feelingText = ['Yeah!', 'Yeah!', 'Yeah♥', 'Yeah!?', 'Yeah...', '"Yeah...'];

        return view('titles/view', compact('meta', 'posts', 'feeling', 'feelingText'));
    }

    /**
     * Post form for communities.
     *
     * @return string
     */
    public function post($tid, $id) : string
    {
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
