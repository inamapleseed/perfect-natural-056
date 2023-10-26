<?php
// ini set php options 
ini_set('memory_limit', '2048M');

// Version
define('VERSION', '2.3.0.2.09');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('admin');