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
		// Dummied out pages
		Router::get('/local_list.json', 'Dummy@dummy', 'local.list');
		Router::get('/check_update.json', 'Dummy@dummy', 'check.update');

		// Communities
		Router::group(['prefix' => 'communities'], function () {
			Router::get('/', 'Community@index', 'community.index');
		});

		// Titles
		Router::group(['prefix' => 'titles'], function () {
			// This is the first page that the applet loads at all after discovery
			Router::get('/show', 'Title.Show@init', 'title.init');
			Router::get('/{tid:a}/{id:a}', 'Title.Community@show', 'title.community');
			Router::get('/{tid:a}/{id:a}/post', 'Title.Community@post', 'title.post');
			Router::get('/{tid:a}/{id:a}/post_memo', 'Title.Community@post_memo', 'title.postmemo');
		});

		// Post handler
		Router::group(['prefix' => 'posts'], function () {
			Router::get('/{id:a}', 'Post@show', 'post.show');
			Router::post('/', 'Post@create', 'post.submit');
		});

		// Settings
		Router::group(['prefix' => 'settings'], function () {
			Router::post('/struct_post', 'Dummy@dummy', 'struct.post');
		});

		// Welcome
		Router::group(['prefix' => 'welcome'], function () {
			Router::get('/3ds', 'Gate@welcome', 'gate.welcome');
			Router::post('/check', 'Gate@check', 'gate.check');
			Router::post('/activate', 'Gate@activate', 'gate.activate');
		});
	});
});
