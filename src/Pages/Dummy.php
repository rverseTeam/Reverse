<?php
/**
 * Holds a dummy page.
 */

namespace Miiverse\Pages;

/**
 * Dummy page.
 *
 * @author Repflez
 */
class Dummy extends Page
{
    /**
     * Serves an empty page, for thosse cases it's needed.
     *
     * @return string
     */
    public function dummy() : string
    {
        return '';
    }
}
