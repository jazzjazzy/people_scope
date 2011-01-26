<?php
session_start();

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 'On');
	ini_set('display_startup_errors', 'On');

$pathSet = ':';
if(strstr(@$_SERVER[SERVER_SIGNATURE], "Win32") !== FALSE){
	$pathSet = ';';
}


	define('SITE_ROOT','http://'.$_SERVER['HTTP_HOST'].'/people_scope/');
	define('DIR_ROOT',$_SERVER['DOCUMENT_ROOT'].'/people_scope/');
	define('TEMPLATE_ROOT',$_SERVER['DOCUMENT_ROOT'].'/people_scope/Templates/');
	define('DB_USER','root');
	define('DB_PASS','password');
	define('DB_HOST','localhost');
	define('DB_DBASE','client_10000000');
	define('DB_TYPE','mysql');
	define('DB_PORT','3306');
	define('DEBUG', false);
	//define('INCLUDE_PATH_DIV', ':'); //TODO: find a way of working out what OS and setting the path divider accordingly



define('CLASS_ROOT', DIR_ROOT.'class/base');
define('ASSETS_ROOT', DIR_ROOT.'assets');
define('CONFIG_ROOT', DIR_ROOT.'config');

set_include_path(get_include_path().PATH_SEPARATOR. DIR_ROOT.'assets/PEAR/');
set_include_path(get_include_path().PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/people_scope/classes/base/');

require_once(CONFIG_ROOT.'/standard.inc.php');
require_once(CONFIG_ROOT.'/constants.php');

if(isset($_REQUEST['js'])){
	echo "const SITE_ROOT = '".SITE_ROOT."';";
	echo "const DIR_ROOT = '".DIR_ROOT."';";
	echo "const TEMPLATE_ROOT = '".TEMPLATE_ROOT."';";
	echo "const DEBUG = '".DEBUG."';";
}

?>
