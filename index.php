<?php
require_once('config/config.php');
require_once('classes/login.class.php');

$login = new login();

$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$year = ((!isset($_REQUEST['year']))? DEFAULT_YEAR : $_REQUEST['year']);

switch($action){
	CASE 'login':
		 $uname = (isset($_REQUEST['uname']))? $_REQUEST['uname'] : '';
		 $pname = (isset($_REQUEST['pname']))? $_REQUEST['pname'] : '';
		 $login->checkUserLogin($uname, $pname);
		 break;
	default : $login->getHomePage();
}

?>