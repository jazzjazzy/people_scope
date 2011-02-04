<?php
require_once 'config/config.php';
require_once DIR_ROOT.'/classes/employmenttype.class.php';

/*$admin->isLoggedIn();*/

$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$year = ((!isset($_REQUEST['year']))? DEFAULT_YEAR : $_REQUEST['year']);

$employmenttype = new employmenttype($year);

switch($action){
	case 'edit' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$employmenttype->editEmploymenttypeDetails($id);
	break;
	
	case 'create' : 
			getSubMenu('create');
			$employmenttype->createEmploymenttypeDetails();
	break;

	case 'show' :
			 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$employmenttype->showEmploymenttypeDetails($id);
	break;
	case 'show-print' :
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$employmenttype->showEmploymenttypePrintDetails($id);
	break;
	
	case 'update' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$employmenttype->updateEmploymenttypeDetails($id);
	break;
	
	case 'save' : 
			getSubMenu('create');
			$employmenttype->saveEmploymenttypeDetails();
	break;

	default :
			getSubMenu('list');
			echo $employmenttype->getEmploymenttypeList();
	break;
}


function getSubMenu($action){
	global $employmenttype;
	/*if($employmenttype->admin->checkAdminLevel(1)){
				$create_css = ($action == 'create')? 'tab-button-select' : 'tab-button'; 
				$staff->template->assign('Menu', '<!--<a href="staff.php?action=show-print" class="tab-button">print Bulk Profile</a>
							<a href="staff.php?action=show-print&id=1" class="tab-button">Fin Bulk Profile</a> 
							<a href="staff.php?action=show-print&id=2" class="tab-button">FAA Bulk Profile</a>
							<a href="staff.php?action=show-print&id=3" class="tab-button">MAAIS Bulk Profile</a>-->
							<a href="staff.php?action=create" class="'.$create_css.'">Add Staff</a>
							<a href="external.php" class="tab-button">List Externals</a>
							<br class="clear"/><div id="tab-button-divider">');
	}*/
}