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

		// Users
		Router::group(['prefix' => 'users'], function () {
			Router::get('/{id:a}', 'User@profile', 'user.profile');
			Router::get('/{id:a}/violators.create', 'Dummy@dummy', 'user.report');
			Router::get('/{id:a}/blacklist.confirm', 'Dummy@dummy', 'user.block');
			Router::post('/{id:a}.follow.json', 'Dummy@dummy', 'user.follow');
			Router::post('/{id:a}.unfollow.json', 'Dummy@dummy', 'user.unfollow');
			Router::get('/{id:a}/favorites', 'Dummy@dummy', 'user.favorites');
			Router::get('/{id:a}/posts', 'Dummy@dummy', 'user.posts');
			Router::get('/{id:a}/following', 'Dummy@dummy', 'user.following');
			Router::get('/{id:a}/followers', 'Dummy@dummy', 'user.followers');
			Router::get('/{id:a}/diary', 'Dummy@dummy', 'user.diary');
			Router::get('/{id:a}/diary/post', 'Dummy@dummy', 'user.diarypost');
		});

		// Titles
		Router::group(['prefix' => 'titles'], function () {
			Router::get('/show', 'Title.Show@init', 'title.init'); // This is the first page that the applet loads at all after discovery
			Router::get('/{tid:a}/{id:a}', 'Title.Community@show', 'title.community');
			Router::get('/{tid:a}/{id:a}/post', 'Title.Community@post', 'title.post');
			Router::get('/{tid:a}/{id:a}/post_memo', 'Title.Community@post_memo', 'title.postmemo');
		});

		// Posts
		Router::group(['prefix' => 'posts'], function () {
			Router::get('/{id:a}', 'Post@show', 'post.show');
			Router::post('/', 'Post@submit', 'post.submit');
			Router::get('/{id:a}/reply', 'Post@reply', 'post.reply');
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
