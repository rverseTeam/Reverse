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
        $titleId = dehashid($tid);
        $posts = [];
        $verified_ranks = [
            config('rank.verified'),
            config('rank.mod'),
            config('rank.admin'),
        ];

        if (!is_array($community) || !is_array($titleId)) {
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
            $user = User::construct($post->user_id);

            $posts[] = [
                'id'       => hashid($post->id),
                'user'     => $user,
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
                'verified' => $user->hasRanks($verified_ranks),
            ];
        }

        $feeling = ['normal', 'happy', 'like', 'surprised', 'frustrated', 'puzzled'];
        $feelingText = ['Yeah!', 'Yeah!', 'Yeah♥', 'Yeah!?', 'Yeah...', 'Yeah...'];

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
        $titleId = dehashid($tid);

        if (!is_array($community) || !is_array($titleId)) {
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

    /**
     * Post memo form for communities.
     *
     * @return string
     */
    public function post_memo($tid, $id) : string
    {
        $community = dehashid($id);
        $titleId = dehashid($tid);

        if (!is_array($community) || !is_array($titleId)) {
            return view('errors/404');
        }

        $meta = DB::table('communities')
                    ->where('id', $community)
                    ->first();

        if (!$meta) {
            return view('errors/404');
        }

        return view('titles/post_memo', compact('meta'));
    }

    /**
     * Check if the current memo is allowed to be posted.
     *
     * @return string
     */
    public function check_memo($tid, $id) : string
    {
        $community = dehashid($id);
        $titleId = dehashid($tid);

        // Check params
        $post_type = $_GET['post_type']; // This one is always sent by the console
        $title_id = $_GET['src_title_id'] ?? 0;
        $has_screenshot = $_GET['has_screenshot'] ?? 0;
        $nex_id = $_GET['dst_nex_community_id'] ?? 0;

        // Post permissions
        $can_post = true;
        $show_community = true;

        if (!is_array($community) || !is_array($titleId)) {
            $show_community = false;
        } else {
            $meta = DB::table('communities')
                        ->where('id', $community)
                        ->first();
        }

        // Base data to send
        $data = [
            'show_community_name' => $show_community,
            'community_path'      => $meta ? route('title.community', compact('tid', 'id')) : '',
            'community_icon_url'  => $meta ? '/img/icons/'.$meta->icon : '',
            'community_name'      => $meta ? $meta->name : '',
            'can_post'            => $has_screenshot ? false : $can_post,
            'olive_community_id'  => $community[0],
            'olive_title_id'      => $title_id[0],
            'message'             => '',
        ];

        return $this->json($data);
    }
}
