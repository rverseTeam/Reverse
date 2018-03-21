<?php
/**
 * Holds the home page.
 */

namespace Miiverse\Pages;

use Miiverse\DB;
use Miiverse\Helpers\ConsoleAuth;

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
        // Activity Feed
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

    /**
     * Latest activity feed data for activity feed.
     *
     * @return string
     */
    public function latestActivityFeed() : string
    {
        return '<div class="js-latest-following-relation-profile-post no-content-window content-loading-window">

  <div class="acted-user-name-content">
    <span class="user-icon-container">
      <img src="http://mii-images.cdn.nintendo.net/4bpwlznjccbs_normal_face.png" class="user-icon">
    </span>
    <span class="acted-user-name"><a href="/users/Emblem04">Hector</a> has started following someone.</span>
  </div>

  <ul class="list-content-with-icon-and-text arrow-list" id="recommend-user-top-content">
    <li class="scroll">
      <span class="user-icon-container ">
        <a class="scroll-focus" data-pjax="1"
         href="/users/h3l10l15k">
          <img src="http://mii-images.cdn.nintendo.net/3kdvbq08xmrbs_normal_face.png" class="user-icon">
        </a>
      </span>

      <div class="body">



<div class="toggle-follow-button">
    <button type="button"
            data-action="/users/h3l10l15k.follow.json"
            class="follow-button"
            
    >Follow</button>
      <button type="button" class="follow-done-button none" disabled>Follow</button>
</div>

        <div class="user-meta">
          <p class="title">
            <span class="nick-name"><a href="/users/h3l10l15k" data-pjax="1">Anankos</a></span>
          </p>
          <p class="text ">I swear, I&#39;m a Fates spoiler personified. Also not associated with the Shepherds. Just saying. In case.

Revelation players, you know the drill. Be the ocean&#39;s gray waves and save my dragon form&#39;s insanity and your world.

Or don&#39;t.

I&#39;m ok with whatever.



(alt for RedSunReptile)



Since Miiverse is probably closing, I put my main chromtacts in my PFP.
          </p>
        </div>
      </div>
    </li>
  </ul>
</div>';
    }
}
