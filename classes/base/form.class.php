<?php

/**
 * Form Class,
 * <br />
 * This class is used to take a input String in a standardised format and convert to a Html Form <br />
 * 
 * Example:<br />
 * <br />
 * $formVars = "<br />
 * Title*:1:select:Ms|Ms,Mrs|Mrs,Miss|Miss,Mr|Mr:::required:notype<br />
 * Home Phone:5:editor::400|400:::optional:notype<br />
 * First Name*:2:text::::required:notype<br />
 * Last Name*:3:text::::required:notype<br />
 * Work Phone:4:text::::optional:notype<br />
 * Home Phone:5:text::::optional:notype<br />
 * Mobile Phone*:6:text::::required:notype<br />
 * Email*:7:text::::required:notype<br />
 * Address:8:textarea::::optional:notype<br />
 * Suburb:9:text::::optional:notype";<br />
 * <br />
 * <br />
 * $form = new form($formVars);<br />
 * $formVal = $form->formHeader('');<br />
 * $formVal .= $form->draw();<br />
 * $formVal .= $form->freeFormSubmit();<br />
 * $formVal .= $form->formFooter();<br />
 * <br />
 * echo $formVal<br />
 * 
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 * @subpackage Base
 */

/**
 * This class is used to take a input String in a standardised format and convert to a Html Form
 * 
 * @package PeopleScope
 * @subpackage Base
 */
class form extends table {
	
	/**
	 * Database object
	 * @var Object 
	 */
	var $db;
	
	/**
	 * Holds string value for form builder
	 * @var String  
	 */
	var $form ="";
	
	/**
	 * Form enctype type
	 * @var String 
	 */
	var $enctype;
	
	/**
	 * Java scripts reloaded to this class 
	 * @var String  
	 */
	var $formScript;
	
	/**
	 * Width size of fck editor
	 * @var Integer
	 */
	var $fck_width = 300;
	
	/**
	 * Height size of fck editor
	 * @var Integer
	 */
	var $fck_height = 500;
	
	//var $template;
	/**
	 * JavaScript handle onChange for Form input types command
	 * 
	 * @todo should be removed for JQuery ready({}) 
	 * @var String
	 */
	var $autoRefresh;
	
	/**
	 * JavaScript handle onClick for Form input types command
	 * use this for check boxes and radio
	 * @todo should be removed for JQuery ready({}) 
	 * @var String
	 */
	var $autoRefreshcheckboxs; 
	
	/**
	 * JavaScript handle onkeyup for Form input types command
	 * user this to to action on an text input with an enter button
	 * @todo should be removed for JQuery ready({}) 
	 * @var String
	 */
	var $autoRefreshInputEnter; 
	
	/**
	 * Url Tor find fck config file 
	 * @var String 
	 */
	var $fck_configUrl;
	
	/**
	 * Consrtructor 
	 * 
	 * @param String $form input values for form information 
	 * @return void
	 */
	function __construct($form=NULL){
		$this->form($form);
	}
	
	/**
	 * Consrtructor php4 
	 * 
	 * @param String $form input values for form information 
	 * @return void
	 */
	function form($form){
		global $global_db;
		$this->db = $global_db;
		$this->form = trim($form);
		//$this->template= new template();
		$this->fck_configUrl = SITE_ROOT."config/fckconfig.js";
	}
	
	/**
	 * Set onSubmit Form scripts
	 *
	 * @todo should be removed for JQuery ready({}) 
	 * @param String $script Javascript command or function
	 * @return void
	 */
	function formScript($script){
		$this->formScript = $script;
	}
	
	/**
	 * Standards Submit Button 
	 * @param String $value Button lable/Name
	 * @return void
	 */
	function freeFormSubmit($value = 'Submit'){
		return '<input type="submit" value="'.$value.'" />';
	}

	/**
	 * Setup the form tag ready for the inputs 
	 *    
	 * @param String $action Form action parameter 
	 * @param String $method Form Method parameter 
	 * @param String $formname Form Name and Id parameter 
	 * @return string 
	 */
	function formHeader($action, $method=NULL, $formname=NULL){
		//find any referance uploading file and set form accordingly
		$columnBreakDown = explode("\n", $this->form);

		foreach ($columnBreakDown AS $value){
			$row = explode(":", $value);
			if ('upload' == trim(strtolower($row[2]))){
				$this->enctype="multipart/form-data";
				$method = "POST";
			}
		}

		//check if method is either GET or POST if not then set POST as default
		$method = trim(strtoupper($method));
		if($method != "POST" && $method != "GET" ){
			$method = "POST";
		}

		//remove any spaces from $formname as can not be used with spaces
		$formname = str_replace(" ", '', $formname);

		$ret = '<form method="'.$method.'" action="'.$action.'"';
		$ret .=(!empty($formname))? ' name="'.$formname.'" id="'.$formname.'"' : '';
		$ret .=($this->enctype)? ' enctype="'.$this->enctype.'"' : '';
		$ret .=($this->formScript)? ' onSubmit="'.$this->formScript.'"' : '';
		$ret .= '>';

		return $ret;
	}
	
	/**
	 * Setup closing form tag
	 *   
	 * @return String 
	 */
	function formFooter(){

		$ret = '</form>';

		return $ret;
	}

	/**
	 * Will Generated the required input fields from the $this->form information
	 * information can be broken up using the string '-------'
	 * 
	 * @param Integer $column Define which column shold be returned
	 * @return String Html version on the generated input fields
	 */
	function draw($column = NULL){

		if($column){
			$this->form = str_replace("------\r\n", "------", $this->form);
			$this->form = str_replace("------\r", "------", $this->form);
			$this->form = str_replace("------\r", "------", $this->form);
			$columnList = explode("------", $this->form);
			$column -= 1;
		}else{
			$columnList[0] = str_replace("------\r\n", '', $this->form);
			$column = 0;
		}

		$count=0;
		//Null Error
		$columnBreakDown = @explode("\n", $columnList[$column]);

		foreach($columnBreakDown AS $value){
			if (!empty($value)){
				$inputfield[] = $this->inputfield($value);
			}
		}
		return @implode("\n", $inputfield);
	}
	
	function edit($input, $type = NULL){
		$fieldData = explode(":::", $input);
		//setting vars
		$file ='';
		$funcVars = '';
		$func_div = false;
		$isdiv = false;
		$nolable = false;
		$valid_str = '';
		$func_script = '';
		$func_class = '';
		$func_id = '';

		if(preg_match( "/^:/",@$fieldData[1])){
			$fieldData[1] = substr($fieldData[1], 1);
		}
		$field = explode(':', trim($fieldData[0]));
		
		
		$type =strtolower(@$field[2]);
		if(file_exists(DIR_ROOT.'/question/'.$type.'.q.php')){
			include_once DIR_ROOT.'/question/'.$type.'.q.php';
		}
		
		$question = new $type();
		
		switch(trim(strtoupper($type))){
			case "DISPLAY": $edit = $question->edit($field, $funcVars, $valid_str.$this->autoRefresh); break;
			case "SETTING": $edit = $question->setting($field); break; 
			default : $edit = $question->edit($field, $funcVars, $valid_str.$this->autoRefresh); break;
		}
		
		//$edit = edit($field, $funcVars, $valid_str.$this->autoRefresh);
		
		return $edit;
	}
	
	/**
	 * Set a alternate FCK config file path
	 * 
	 * @param String $url Url to new config file
	 * @return void
	 */
	function SetFckConfigUrl($url){
		$this->fck_configUrl = $url;
	}
	
	/**
	 * Takes in a standard single row from $this->form information and 
	 * generated and html form input string 
	 *  
	 * @param String $input string eg. State:10:select:ACT|ACT,NSW|NSW,VIC|VIC,QLD|QLD,SA|SA,NT|NT,WA|WA,TAS|TAS:::optional:notype
	 * @return Html form input 
	 */
	function inputfield($input){
		$fieldData = explode(":::", $input);
		//setting vars
		$file ='';
		$funcVars = '';
		$func_div = false;
		$isdiv = false;
		$nolable = false;
		$valid_str = '';
		$func_script = '';
		$func_class = '';
		$func_id = '';

		if(preg_match( "/^:/",@$fieldData[1])){
			$fieldData[1] = substr($fieldData[1], 1);
		}
		$field = explode(':', trim($fieldData[0]));
		
		$valid_str ='';
		if(!empty($fieldData[1])){
			$valid = explode(':', trim($fieldData[1]));
			$valid_str = "validitycheck=\"$field[1];$field[0];$valid[0];$valid[1];0;-1\"";
		}else{
			@$valid_str = "validitycheck=\"$field[1];$field[0];optional;notype;0;-1\"";
		}
		if (!isset($field[3])){
			$field[3]=''; // if value not set then create and set to blank
		}
		//nulled Error as value can be blank in this case
		if (@isset($field[4])){

			$func = explode(',', trim($field[4]));

			foreach($func AS $value){
				if(preg_match( "/^J\|/", $value)){
					$value=str_replace("J|",'',$value);
					$func_script = trim($value)." ";
				}
				if(preg_match( "/^C\|/", $value)){
					$value=str_replace("C|",'',$value);
					$func_class = "class=".trim($value)." ";
				}
				if(preg_match( "/^I\|/", $value)){
					$value=str_replace("I|",'',$value);
					$func_id = "id=".trim($value)." ";
				}
				if(preg_match( "/^D\|/", $value)){
					$value=str_replace("D|",'',$value);
					$func_div = "<div id=".trim($value).">";
				}
				if(preg_match( "/^L\|/", $value)){
						$value=str_replace("L|",'',$value);
						if ($value == "NO_LABLE") $nolable = true;
				}
			}

			$funcVars = $func_script.$func_class.$func_id;

		}
		
		$type =strtolower(@$field[2]);
		if(file_exists(DIR_ROOT.'/question/'.$type.'.q.php')){
			include_once DIR_ROOT.'/question/'.$type.'.q.php';
		}
		
		$question = new $type();
		
		$file = $question->display($field, $funcVars, $valid_str.$this->autoRefresh);
		
		if ($nolable){
			$isdiv = (@$func_div)?"</div>":"";
			return $file;
		}else{
			$isdiv = (@$func_div)?"</div>":"";
			return "<div><div class=\"lable\">$field[0]</div> <div class=\"field\">".@$func_div.$file.$isdiv."</div></div><div style=\"clear:both\"></div>\n";
		}
	}
	
	/**
	 * Set the AutoRefresh function command 
	 * 
	 * @todo should be removed for JQuery ready({}) 
	 * @param String $command Javascript code or function 
	 * @return void
	 */
	function setAutoRefresh($command){
		$this->autoRefresh = " onChange=\"".trim($command)."\"";
		$this->autoRefreshcheckboxs = " onClick=\"".trim($command)."\"";
		$this->autoRefreshInputEnter = " onkeyup=\"entercontents('".trim($command)."', event);\"";
	}
	
	/**
	 * Remove the AutoRefresh function command  
	 * 
	 * @todo should be removed for JQuery ready({}) 
	 * @return void
	 */
	function unsetAutoRefresh(){
		$this->autoRefresh = NULL;
		$this->autoRefreshcheckboxs = NULL;
		$this->autoRefreshInputEnter = NULL;
	}
}
?>
