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
        $notifications = [];

        $notifications_pre = DB::table('notifications')
                    ->where('to', $local_user->id)
                    ->orderBy('date', 'desc')
                    ->get();

        //var_dump($notifications_pre);

        foreach ($notifications_pre as $notification) {
            $user = User::construct($notification->from);
            $post = [];
            $comment = [];

            // Post
            //if ($notification->type == 4) {
                $post = DB::table('posts')
                            ->where('id', 1)
                            ->first();

                // Checking if it exists and if string is above 17 chars, minify it
                if ($post->content) {
                    if (strlen($post->content) > 17) {
                        $post->content = substr($post->content, 0, 17).'...';
                    }
                }
            //}

            var_dump($post);

            // Comment
            if ($notification->comment_id > 0) {
                $comment = DB::table('comments')
                            ->where('id', $notification->$comment_id)
                            ->first();

                // Checking if it exists and if string is above 17 chars, minify it
                if ($comment->content) {
                    if (strlen($comment->content) > 17) {
                        $comment->content = substr($comment->content, 0, 17).'...';
                    }
                }
            }

            $notifications[] = [
                'type'      => $notification->type,
                'user'      => $user,
                'post'      => $post,
                'comment'   => $comment,
                'date'      => $notification->date,
            ];
        }

        //var_dump($notifications);

        DB::table('notifications')
                    ->where([
                        ['to', $local_user->id],
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
