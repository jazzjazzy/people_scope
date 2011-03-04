<?php

//check for file if ajax we are one dir down, if not we are at the root
if(is_file('../config/config.php')){
	require_once '../config/config.php';
}else{
	require_once 'config/config.php';
}


$action = $_REQUEST['action'];

$checkbox = new checkbox();

switch($action){
	case 'add-fields' : 
			$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
			echo $checkbox->addFields($id);
			break;
	case 'add-tracker' :
			$id =(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
			echo $checkbox->addTracker($id);
}

class yes_no{
	
	public function __construct(){
		$this->template = new template('blank');
	}	
	
	
	var $template;
	function display($field,$funcVars, $valid_str){
		$file = '<input type="radio" name="'.$field[1].'" value="Yes" '.$funcVars.' '.$valid_str.'>Yes';
		$file .= '<input type="radio" name="'.$field[1].'" value="No" '.$funcVars.' checked '.$valid_str.'>No';
		return $file;
	}
	
	function edit($field,$funcVars, $valid_str){
	
		$html = '
		Tracking:<br />
		Any test contain these word:<br />';
		$html .= '<input type="radio" name="'.$field[1].'" value="Yes" '.$funcVars.' '.$valid_str.'>Yes';
		$html .= '<input type="radio" name="'.$field[1].'" value="No" '.$funcVars.' checked '.$valid_str.'>No';
		$html .= '<div style="font-size:9px">(seperate with commas e.g word1, word2)</div>
		<br />';
		
		return $html;
	}
	
	function setting($field){
		
		$html = '
		Is this field required: 
		<input type="radio" value="yes" name="required">yes
		<input type="radio" value="no" name="required">no';	
	
		
		return $html;
	}
}
?>