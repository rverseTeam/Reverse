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
		// Local list check
		Router::get('/local_list.json', 'Dummy@dummy', 'local.list');
		Router::get('/check_update.json', 'Dummy@dummy', 'check.update');

		// Welcome
		Router::group(['prefix' => 'welcome'], function () {
			Router::get('/3ds', 'Gate@welcome', 'gate.welcome');
			Router::post('/check', 'Gate@check', 'gate.check');
			Router::post('/activate', 'Gate@activate', 'gate.activate');
		});

		// Titles
		Router::group(['prefix' => 'titles'], function () {
			// This is the first page that the applet loads at all after discovery
			Router::get('/show', 'Title.Show@init', 'title.init');
			Router::get('/{tid:a}/{id:a}', 'Title.Community@show', 'title.community');
			Router::get('/{tid:a}/{id:a}/post', 'Title.Community@post', 'title.post');
			Router::get('/{tid:a}/{id:a}/post_memo', 'Title.Community@post_memo', 'title.postmemo');
		});

		// Communities
		Router::group(['prefix' => 'communities'], function () {
			Router::get('/', 'Community@index', 'community.index');
		});
	});
});
