<?php
/*
 * Router paths
 */

// Define namespace
namespace Miiverse;

use Phroute\Phroute\Exception\HttpRouteNotFoundException;

// Maintenance check
Router::filter('maintenance', 'checkMaintenance');

Router::group(['before' => 'maintenance'], function () {
	// 3DS Only page
	Router::get('/', 'PC@warning', 'pc.teaser');

	// Titles
    Router::group(['prefix' => 'titles'], function () {
    	// CTR loads this page first at all. Anything else can be done by us.
		Router::get('/show', 'Titles.Show@init', 'titles.init');
    });
});
