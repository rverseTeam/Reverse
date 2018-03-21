<?php
/**
 * Holds the settings page.
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\CurrentSession;

/**
 * Settings page.
 *
 * @author Cyuubi
 */
class Settings extends Page
{
    /**
     * User has completed a tutorial part, set it in database.
     *
     * @return string
     */
    public function tutorial_post() : string
    {
        $user = CurrentSession::$user;
        $userid = $user->id;
        $tutorial_name = $_POST['tutorial_name'];
        $database_key = '';

        // database key to put to true
        switch ($tutorial_name) {
            case 'my_news':
                $database_key = 'news_dot';
                break;
            default:
                $database_key = 'nokey';
                break;
        }

        header('Content-Type: application/json; charset=utf-8');
        if ($database_key == 'nokey') return '{"success":0}';

        DB::table('users')
            ->where('user_id', '=', $userid)
            ->update([$database_key => 1]);

        return '{"success":1}';
    }
}
