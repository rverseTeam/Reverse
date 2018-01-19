<?php
/*
 * Router paths
 */

// Define namespace
namespace Miiverse;

use Phroute\Phroute\Exception\HttpRouteNotFoundException;

// Filters
Router::filter('maintenance', 'checkMaintenance');
Router::filter('auth', 'checkConsoleAuth');

Router::group(['before' => 'maintenance'], function () {
	// Development teaser
	Router::get('/', 'PC@warning', 'pc.teaser');

	// 3DS required to load these pages
	Router::group(['before' => 'auth'], function () {
		// Welcome page
		Router::get('/welcome', 'Gate@welcome', 'gate.welcome');

		// Titles
		Router::group(['prefix' => 'titles'], function () {
			// CTR loads this page first at all. Anything else can be done by us.
			Router::get('/show', 'Title.Show@init', 'title.init');
		});

		// Communities
		Router::group(['prefix' => 'communities'], function () {
			Router::get('/', 'Community@index', 'community.index');
		});
	});
});
