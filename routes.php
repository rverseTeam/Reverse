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
});
