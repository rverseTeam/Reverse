<?php
/**
 * Holds the news page.
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\Helpers\ConsoleAuth;

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
        $user = CurrentSession::$user;
        return view('news/my_news', compact('user'));
    }
}
