<?php
/**
 * Holds the home page.
 */

namespace Miiverse\Pages;

/**
 * Home page.
 *
 * @author Repflez
 */
class PC extends Page
{
    /**
     * Serves the site index.
     *
     * @return string
     */
    public function warning() : string
    {
        return view('errors/teaser');
    }
}
