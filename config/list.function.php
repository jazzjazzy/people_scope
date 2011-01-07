<?php
require_once('config.php');


function headOffice(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/office.class.php');
	$cat="";
	$office = new office();

	$listOffice = $office->getOfficeAll();
	$cat .= "<option value=\"NULL\"></option>\n";
	foreach ($listOffice as $key => $value) {
			$cat .= "<option value=\"".$key."\" selected>".$value['name']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function department($_INPUT){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/office.class.php');
	$cat="";
	$office = new office();

	$listOffice = $office->getDepartmentsByOffice(1);

	$cat .= "<option value=\"NULL\"></option>\n";
	foreach ($listOffice as $key => $value) {
			$selected = ($key == $_INPUT)? " selected" : '';
			$cat .= "<option value=\"".$key."\"".$selected.">".$value."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function role($_INPUT){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/office.class.php');
	$cat="";
	$office = new office();

	$listRoles = $office->getRoleAll();
	$cat .= "<option value=\"NULL\"></option>\n";
	foreach ($listRoles as $key => $value) {
			$selected = ($key == $_INPUT)? " selected" : '';
			$cat .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}


function storeRoles($_INPUT){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/store.class.php');
	$cat="";
	$store = new store();

	$listStoreRoles = $store->getStoreRoles();
	$cat .= "<option value=\"NULL\"></option>\n";
	foreach ($listStoreRoles as $key => $value) {
			$selected = ($key == $_INPUT)? " selected" : '';
			$cat .= "<option value=\"".$key."\"".$selected.">".$value['name']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function storeList(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/store.class.php');
	$cat="";
	$store = new store();

	$listStore = $store->getListOfStores();
	$cat .= "<option value=\"-1\"></option>\n";

	foreach ($listStore as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value['location']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function storeListString(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/store.class.php');
	$cat="";
	$store = new store();

	$listStore = $store->getListOfStores();

	foreach ($listStore as $key => $value) {
			$cat[] = array('label'=>$value['location'],'value'=>$value['storeLoc_id']);
	}
	return $cat;
}



function getAdvertising($id = Null){
	require_once('class/jobs.class.php');
	$cat="";
	$job = new job();

	$list = ($id == NULL)?$job->getAdvertisingCosts():$job->getAdvertisingCosts($id);
	$cat .= "<table cellpadding=\"0\" cellspacing=\"0\">\n";

	$sum = $list['sumtotal'];
	$paper = $list['newspaper'];
	unset($list['sumtotal']);
	unset($list['newspaper']);

	foreach ($list as $k=> $v) {
		foreach ($v as $key=> $value) {
			//pp($value['referral_id']);
			$extra = ($value['referral_id'] == 3)? "<input style=\"width:200px\" type=\"text\" name=\"newspapers\" value=\"".$paper[0]['cost']."\" validitycheck=\"\" />" : '&nbsp;';
			$cat .= "<tr><td>".$value['name']."</td><td>$<input style=\"width:60px\" type=\"text\" id=\"cost_".$value['referral_id']."\" name=\"cost[".$value['referral_id']."]\" value=\"".$value['cost']."\" onchange=\"CalTotal()\" validitycheck=\"\"  /></td><td>".$extra."</td></tr>\n";
		}
	}
	$cat .= "<tr><td style=\"border-top:1px solid #000\">Total</td><td style=\"border-top:1px solid #000\">$<input id=\"sumtotal\" style=\"width:60px\" type=\"text\" name=\"sumtotal\" value=\"".$sum[0]['cost']."\"  validitycheck=\"\" /></td><td>&nbsp;</td></tr>\n";
	$cat .= "</table>\n";
	return $cat;
}

function headDeptarment(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/office.class.php');
	$cat="";
	$office = new office();

	$listDept = $office->getDepartmantAll();
	$cat .= "<option value=\"NULL\">All</option>\n";
	foreach ($listDept as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function headRole(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/office.class.php');
	$cat="";
	$office = new office();

	$listRoles = $office->getRoleAll();
	$cat .= "<option value=\"NULL\">All</option>\n";
	foreach ($listRoles as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function stateList($_INPUT){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/store.class.php');
	$cat="";
	$state = new store();

	$stateList = $state->getListOfStates();
	$cat .= "<option value=\"NULL\"></option>\n";
	foreach ($stateList as $key => $value) {
			$selected = ($key == $_INPUT)? " selected" : '';
			$cat .= "<option value=\"".$key."\"".$selected.">".$value['name']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}


function stateListFront(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/store.class.php');
	$cat="";
	$state = new store();

	$stateList = $state->getListOfStates();
	$cat .= "<option value=\"NULL\">All</option>\n";
	foreach ($stateList as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value['name']."</option>\n";
	}
	return $cat;
}

function roleListFront(){
	require_once('admin/class/role.class.php');
	$cat="";
	$role = new role();

	$roleList = $role->getStoreRoles();
	$cat .= "<option value=\"NULL\">All</option>\n";
	foreach ($roleList as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value['name']."</option>\n";
	}
	return $cat;
}

function CheckState(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(10);
	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	 return implode('<br>',$listvalues);
}

function getCatagoryList(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$catlist = $questionObj->listCatagory();
	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($catlist AS $key=>$value){
		$listvalues[] = "<option value=\"".$key."\">".$value."</option>\n";
	}
	return implode('<br>',$listvalues);
}


function CheckCitizenship(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(13);

	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	return implode('<br>',$listvalues);
}

function CheckOtherStore(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(23);

	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	 return implode('<br>',$listvalues);
}

function CheckEducation(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(14);

	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	 return implode('<br>',$listvalues);
}

function CheckExperience(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(17);

	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	 return implode('<br>',$listvalues);
}

function CheckSalary(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/question.class.php');

	$questionObj = new question();

	$stateList = $questionObj->getMultiByQuestionId(20);

	$listvalues[] = "<option value=\"-1\"></option>\n";
	foreach($stateList AS $value){
		$listvalues[] = "<option value=\"".$value['value']."\">".$value['value']."</option>\n";
	}
	 return implode('<br>',$listvalues);
}

function listTemplateoffice(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/jobs.class.php');
	$cat="";
	$template = new job();

	$templateList = $template->getListOfTemplates(OFFICE);
	$cat .= "<option value=\"-1\"></option>\n";
	foreach ($templateList as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value['title']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;

}

function listTemplateStore(){
	require_once('class/jobs.class.php');
	$cat="";
	$template = new job();

	$templateList = $template->getListOfTemplates(STORE);
	$cat .= "<option value=\"-1\"></option>\n";
	foreach ($templateList as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value['title']."</option>\n";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";
	return $cat;
}

function listStatus(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/careers/admin/class/jobs.class.php');
	$cat="";
	$template = new job();

	$templateList = $template->getListOfStatus();
	$cat .= "<option value=\"-1\">All</option>\n";
	$cat .= "<OPTGROUP LABEL=\"Selected List\">";
	$cat .= "<option value=\"SELECTED\">Selected</option>\n";
	$cat .= "</OPTGROUP>";
	$cat .= "<OPTGROUP LABEL=\"By Status\">";
	foreach ($templateList as $key => $value) {
			$cat .= "<option value=\"".$key."\">".$value."</option>\n";
	}
	$cat .= "</OPTGROUP>";
	return $cat;
}


function listEmailTypes(){
	require_once('class/contact.class.php');
	$cat="";
	$template = new contact();

	$catList = $template->getListOfEmailTypes();
	$cat .= "<option value=\"-1\"></option>\n";
	foreach ($catList as $key => $value) {
			$cat .= "<OPTGROUP LABEL=\"".$value['catagory_name']."\">";
			foreach ($value['list'] as $keyEmail => $valueEmail) {
				$cat .= "<option value=\"".$valueEmail['contact_type_id']."\">".$valueEmail['title']."</option>";
			}
			$cat .= "</OPTGROUP>";
	}
	//$cat .= "<option value=\"NEW\" style=\"background:red;color:white\">Add New</option>";

	return $cat;
}

function storeArray(){
		require_once('admin/class/store.class.php');
		$stores = new store();
		$catList = $stores->listStoreByState();
		foreach ($catList as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$cat2[] = "\t\t\t[".$value1['storeLoc_id'].",'".$value1['location']."',false]";
			}
			$cat .= "stateStores.push([".$key.",[\n" .implode(",\n" ,$cat2). "]]);\n";
			unset($cat2);
	}
	return $cat;
}

function roleArray(){
		require_once('admin/class/role.class.php');
		$role = new role();
		$catList = $role->listRoleByDept();
		foreach ($catList as $key => $value) {
			foreach ($value as $key1 => $value1) {
				$cat2[] = "\t\t\t[".$key1.",'".$value1['name']."',false]";
			}
			$cat .= "DeptRoles.push([".$key.",[\n" .implode(",\n" ,$cat2). "]]);\n";
			unset($cat2);
	}
	return $cat;
}

/**
* Defualt function to generate returned records into formated option tags
**/
function DatabaseList($sql, $label, $value=NULL){
	$db = global_db();

	try {
	   $result = $db->CacheExecute($sql);
	} catch (exception $e) {
	   pp($e,1);
	   return;
	}

	$cat = "<option value=\"-1\">All</option>\n";
	foreach ($result as $row) {
		$cat .= "<option";
		if ($value){
			$cat .= " value = \"".$row[$value]."\"";
		}
		$cat .= ">".$row[$label]."</option>\n";
	}

	return $cat;
}

function dateFields($name, $date, $setting){
	$options = create_date_field($name, $date, 20, 2006);

	$select = "<select name=\"".$name."__day\"". $setting .">".$options['day']."</select>";
	$select .= "<select name=\"".$name."__month\"". $setting .">".$options['month']."</select>";
	$select .= "<select name=\"".$name."__year\"". $setting .">".@$options['year']."</select>";

	return $select;
}


?>
