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
		Router::get('/welcome/3ds', 'Gate@welcome', 'gate.welcome');
		Router::post('/welcome/check', 'Gate@check', 'gate.check');
		Router::post('/welcome/activate', 'Gate@activate', 'gate.activate');

		// Titles
		Router::group(['prefix' => 'titles'], function () {
			// This is the first page that the applet loads at all after discovery
			Router::get('/show', 'Title.Show@init', 'title.init');
			Router::get('/{tid:a}/{id:a}', 'Title.Community@show', 'title.community');
			Router::get('/{tid:a}/{id:a}/post', 'Title.Community@post', 'title.post');
		});

		// Communities
		Router::group(['prefix' => 'communities'], function () {
			Router::get('/', 'Community@index', 'community.index');
		});
	});
});
