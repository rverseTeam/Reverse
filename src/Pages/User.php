<?php
/**
 * Holds the profile page.
 */

namespace Miiverse\Pages;

use Miiverse\User as Profile;

/**
 * Profile page.
 *
 * @author Repflez
 */
class User extends Page
{
    /**
     * Serves the profile index.
     *
     * @return string
     */
    public function profile(string $name) : string
    {
        $profile = Profile::construct(urldecode($name));

        if (!$profile || $profile->id === 0) {
            return view('errors/404');
        }

        return view('user/profile', compact('profile'));
    }
}
