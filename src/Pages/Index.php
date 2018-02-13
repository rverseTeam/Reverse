<?php
/**
 * Holds the home page.
 */

namespace Miiverse\Pages;

use Miiverse\Helpers\ConsoleAuth;
use Miiverse\DB;

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
        // Normally on Wii U and 3DS this would be Activity Feed but since it isn't implemented we're just going to return
        if (ConsoleAuth::$paramPack['platform_id'] != 2) {
            return view('index/index');
        }

        // Fetch the last 10 communities
        $communities = [
            'general' => DB::table('communities')
                            ->where('type', '=', 0)
                            ->latest('created')
                            ->limit(6)
                            ->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
            'game' => DB::table('communities')
                        ->where([
                            ['type', '>', 0],
                            ['type', '<', 4],
                        ])
                        ->latest('created')
                        ->limit(6)
                        ->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
            'special' => DB::table('communities')
                            ->where('type', '=', 4)
                            ->latest('created')
                            ->limit(6)
                            ->get(['id', 'title_id', 'name', 'icon', 'type', 'platform']),
        ];

        return view('index/index', compact('communities'));
    }
}
