<?php
/*
 * Router paths
 */

// Define namespace

namespace Miiverse;

// Filters
Router::filter('maintenance', 'checkMaintenance');
Router::filter('auth', 'checkConsoleAuth');

Router::group(['before' => 'maintenance'], function () {
    // Development teaser
    Router::get('/', 'PC@warning', 'pc.teaser');

    // Welcome guest AKA "you need a NNID to use this"
    // Needsto be outside the group so it doesn't get caught by auth
    Router::get('/welcome_guest', 'Gate@guest', 'welcome.guest');

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
            Router::get('/{id}', 'User@profile', 'user.profile');
            Router::get('/{id}/violators.create', 'Dummy@dummy', 'user.report');
            Router::get('/{id}/blacklist.confirm', 'Dummy@dummy', 'user.block');
            Router::post('/{id}.follow.json', 'Dummy@dummy', 'user.follow');
            Router::post('/{id}.unfollow.json', 'Dummy@dummy', 'user.unfollow');
            Router::get('/{id}/favorites', 'Dummy@dummy', 'user.favorites');
            Router::get('/{id}/posts', 'Dummy@dummy', 'user.posts');
            Router::get('/{id}/following', 'Dummy@dummy', 'user.following');
            Router::get('/{id}/followers', 'Dummy@dummy', 'user.followers');
            Router::get('/{id}/diary', 'Dummy@dummy', 'user.diary');
            Router::get('/{id}/diary/post', 'Dummy@dummy', 'user.diarypost');
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
            Router::post('/{id:a}/empathies', 'Post@yeahs', 'post.empathies');
            Router::post('/{id:a}/empathies.delete', 'Post@removeYeahs', 'post.empathiesdelete');
        });

        // Comments
        Router::group(['prefix' => 'replies'], function () {
            Router::post('/{id:a}/empathies', 'Post@replyYeahs', 'comment.empathies');
            Router::post('/{id:a}/empathies.delete', 'Post@replyRemoveYeahs', 'comment.empathiesdelete');
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
