<?php
/**
 * Holds the news page.
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\CurrentSession;
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
        $user = User::construct(urldecode(CurrentSession::$user->username));
        return view('news/my_news', compact('user'));
    }
}
