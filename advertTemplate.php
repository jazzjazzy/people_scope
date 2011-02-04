<?php
require_once 'config/config.php';
require_once DIR_ROOT.'/classes/advertTemplate.class.php';

/*$admin->isLoggedIn();*/

$action = (!isset($_REQUEST['action']))? '' : $_REQUEST['action'];
$year = ((!isset($_REQUEST['year']))? DEFAULT_YEAR : $_REQUEST['year']);

$advertTemplate = new advertTemplate($year);

switch($action){
	case 'edit' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$advertTemplate->editAdvertTemplateDetails($id);
	break;
	
	case 'create' : 
			getSubMenu('create');
			$advertTemplate->createAdvertTemplateDetails();
	break;

	case 'show' :
			 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$advertTemplate->showAdvertTemplateDetails($id);
	break;
	case 'show-print' :
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$advertTemplate->showAdvertTemplatePrintDetails($id);
	break;
	
	case 'update' : 
			$id = (!isset($_REQUEST['id']))? NULL : $_REQUEST['id'];
			$advertTemplate->updateAdvertTemplateDetails($id);
	break;
	
	case 'save' : 
			getSubMenu('create');
			$advertTemplate->saveAdvertTemplateDetails();
	break;

	default :
			getSubMenu('list');
			echo $advertTemplate->getAdvertTemplateList();
	break;
}


function getSubMenu($action){
	global $advertTemplate;
	/*if($advertTemplate->admin->checkAdminLevel(1)){
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