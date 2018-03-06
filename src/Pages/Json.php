<?php
/**
 * Holds a JSON page.
 */

namespace Miiverse\Pages;

/**
 * JSON handlers.
 *
 * @author Cyuubi
 */
class Json extends Page
{
    /**
     * Gets users updates.
     *
     * @return string
     */
    public function checkUpdate() : string
    {
        header('Content-Type: application/json; charset=utf-8');

        return '{"success":1,"admin_message":{"unread_count":0},"mission":{"unread_count":0},"news":{"unread_count":0},"message":{"unread_count":0}}';
    }

    /**
     * Gets users local titles list(?).
     *
     * @return string
     */
    public function localList() : string
    {
        header('Content-Type: application/json; charset=utf-8');

        return '{"success":1,"epoch_now":'.time().',"local_list":"0"}';
    }

    /**
     * Stubbed JSON page.
     *
     * @return string
     */
    public function stub() : string
    {
        header('Content-Type: application/json; charset=utf-8');

        return '{"success":1}';
    }
}
