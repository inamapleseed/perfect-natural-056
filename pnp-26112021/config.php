<?php
// Hackes
define('URL', $_SERVER['HTTP_HOST'] . str_replace('/pnp-26112021', '', dirname($_SERVER['PHP_SELF']))."/");
define('BASE_DIR', str_replace(DIRECTORY_SEPARATOR,"/",str_replace(DIRECTORY_SEPARATOR . "pnp-26112021", "", realpath(dirname(__FILE__))))."/");
// Dynamic Protocol Settings
$protocol = "http://";
if($_SERVER["SERVER_PORT"] == 443){	$protocol = "https://";}
// HTTP Protocol
define('HTTP_SERVER', $protocol . URL. 'pnp-26112021/');
define('HTTPS_SERVER', $protocol . URL. 'pnp-26112021/');

// HTTP Protocol Catalog
define('HTTP_CATALOG', $protocol . URL);
define('HTTPS_CATALOG', $protocol . URL);

// Directory
define('DIR_APPLICATION', BASE_DIR. 'pnp-26112021/');
define('DIR_SYSTEM', BASE_DIR. 'system/');
define('DIR_IMAGE', BASE_DIR. 'image/');
define('DIR_LANGUAGE', BASE_DIR. 'pnp-26112021/language/');
define('DIR_TEMPLATE', BASE_DIR. 'pnp-26112021/view/template/');
define('DIR_CONFIG', BASE_DIR. 'system/config/');
define('DIR_CACHE', BASE_DIR. 'system/storage/cache/');
define('DIR_DOWNLOAD', BASE_DIR. 'system/storage/download/');
define('DIR_LOGS', BASE_DIR. 'system/storage/logs/');
define('DIR_MODIFICATION', BASE_DIR. 'system/storage/modification/');
define('DIR_UPLOAD', BASE_DIR. 'system/storage/upload/');
define('DIR_CATALOG', BASE_DIR. 'catalog/');
define('DIR_EXCEL', BASE_DIR. 'system/storage/excel/');
define('DIR_EXCEL_TPL', BASE_DIR. 'system/storage/excel/tpl/');
define('DIR_EXCEL_GEN', BASE_DIR. 'system/storage/excel/tpl/_generated/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'pnpcomsg_db');
define('DB_PASSWORD', 'H%+Z5U&l[77%');
define('DB_DATABASE', 'pnpcomsg_db');
define('DB_PORT', '3306');
define('DB_PREFIX', 'perfectnatural_');

define('BANNER_EXTRA', true);
define('ADVANCE_PASSWORD', false);
define('ADMIN_FOLDER', 'pnp-26112021');

// Show only allowed payment and shipping modules in backend, if new module installed, kindly add into the list below;
$payments = array(
	'bank_transfer',
	'cod',
	'free_checkout',
	'paydollar',
	'pp_express',
	'paynow',
	'omise',
	'fomopay',
	// 'omise_offsite',
	'omise_paynow',
	'stripe',
	'hoolah',
	'dbs_paynow_qr',
	'mpgs',
);

$shipping = array(
	//'category_product_based',
	'flat',
	//'formula_based',
	'free',
	'pickup',
	'hitdhlexpress',
	'lalamove',
	'ninja_van',
);

$hidden_order_total = array(
	'ordertotal_discount',
);

$modules = array();

define('ALLOWED_PAYMENTS',$payments);
define('ALLOWED_SHIPPING',$shipping);
define('HIDDEN_MODULES',$modules);
define('HIDDEN_ORDER_MODULES',$hidden_order_total);