<?php
$folder = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
chdir($folder);

require_once('config.php');

$domain = parse_url(HTTPS_SERVER);
$host = $domain['host'];
$_SERVER['SERVER_NAME'] = $host;

$_GET['route'] = 'extension/module/instagramshopgallery/callback';

require_once('index.php');
