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
class Index extends Page
{
    /**
     * Serves the site index.
     *
     * @return string
     */
    public function index() : string
    {
        return view('index/index');
    }
}
