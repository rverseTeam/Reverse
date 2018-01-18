<?php
/**
 * TestVerse Index File
 */

// Declare namespace
namespace Miiverse;

// Include app
require_once __DIR__ . '/../core.php';

// Start output buffering
ob_start(config('performance.compression') ? 'ob_gzhandler' : null);

// Handle requests
echo Router::handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
