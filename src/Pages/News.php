<?php
/**
 * Holds the news page.
 */

namespace Miiverse\Pages;

use Miiverse\CurrentSession;
use Miiverse\DB;
use Miiverse\User;

/**
 * News page.
 *
 * @author Cyuubi
 */
class News extends Page
{
    /**
     * News index.
     *
     * @return string
     */
    public function my_news() : string
    {
        $local_user = CurrentSession::$user;

        $notifications_pre = DB::table('notifications')
                    ->where('to', $user->id)
                    ->orderBy('date', 'desc');

        foreach ($notifications_pre as $notification) {
            $user = User::construct($notification->from);
            $post = null;
            $comment = null;

            // Post
            if ($notification->post_id != 0) {
                $post = DB::table('posts')
                            ->where('id', $notification->$post_id)
                            ->first();
            }

            // Comment
            if ($notification->comment_id != 0) {
                $comment = DB::table('comments')
                            ->where('id', $notification->$comment_id)
                            ->first();
            }

            $notifications[] = [
                'type'      => $notification->type,
                'user'      => $user,
                'post'      => $post,
                'comment'   => $comment,
                'date'      => $notification->date,
            ];
        }

        DB::table('notifications')
                    ->where([
                        ['to', $user->id],
                        ['seen', 0],
                    ])
                    ->update(['seen' => 1]);

        // laravel being a piece of shit
        if ($notifications == null) {
            $notifications = null;
        }

        return view('news/my_news', compact('local_user', 'notifications'));
    }
}
