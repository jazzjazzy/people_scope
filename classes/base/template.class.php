<?php


class template{

	private $db_connect;
	private $db;
	private $layout = 'layout.tpl.html';
	private $headerArray;
	private $filterArray;
	private $template;
	
	

	public function __construct($layout = NULL){
	
		$this->db = new db();
		//pp($this->db);
		//$this->db_connect = $this->db->db;

		//if ($this->db->lastError){
		 //	$this->lastError = $this->db->lastError;
		//	return false;
		//}
		
		
		$this->template = new stdClass(); 
		
		if($layout){
			$this->template->layout = fread(fopen( DIR_ROOT."/templates/".$layout, 'r'), filesize(DIR_ROOT."/templates/".$layout));
		}else{
			$this->template->layout = fread(fopen( DIR_ROOT."/templates/layout.tpl.html", 'r'), filesize(DIR_ROOT."/templates/layout.tpl.html"));
		}
		//$this->template->layout = str_replace("{*SITE_TYPE*}", SITE_TYPE, $this->template->layout );
	}
	
	public function __destruct(){

	}
	
	public function page($field){
		$val = fread(fopen( DIR_ROOT."/templates/".$field, 'r'), filesize(DIR_ROOT."/templates/".$field));
		
		$this->template->layout = str_replace("{*CONTENT*}", $val, $this->template->layout );
	}
	
	public function insert($template){
		$this->chunk = fread(fopen( DIR_ROOT."/templates/".$template, 'r'), filesize(DIR_ROOT."/templates/".$template));
		return $this->chunk; 
	}
	
	public function content($field){
		
		$this->template->layout = str_replace("{*CONTENT*}", $field, $this->template->layout );
	}

	public function assign($field, $value, &$tpl=NULL ){
		
		if($tpl){
			$tpl = str_replace('{*'.$field.'*}', $value, $tpl );
		}else{
			$this->template->layout = str_replace('{*'.$field.'*}', $value, $this->template->layout );
		}
	}
	
	public function fetch(&$tpl=NULL){
		if($tpl){
			$striped_layout = $this->strip_tags($tpl);
		}else{
			$striped_layout = $this->strip_tags($this->template->layout);
		}
		return $striped_layout;
	}
	
	public function display(){
		echo $this->fetch();
	}
	
	public function formatBoolean($value){
			if ($value){
				return 'Yes';
			}else{
				return 'No';
			}
			
			return "<div class=\"error\">error??</div>";
	}
	
	public function formatValue($value, $msg){
			if (!empty($value)){
				return $value;
			}else{
				return $msg;
			}
			
			return "<div class=\"error\">error??</div>";
	}
	
	public function getListTable($table, $value, $idField, $valueField, $selectBox = NULL, $WHERE = NULL){

			//set vars 
			$retValue = '';
			$selected = '';
			$other = '';

			$sql = "SELECT * FROM $table "; 

			if(!empty($selectBox)){
				$sql .= $WHERE; 
				$sql .= " ORDER BY $valueField";
				foreach ($this->db->select($sql) as $key=>$row){

					$selected = ($row[$idField] == $value)? 'SELECTED' : '';
					if (trim(strtoupper($row[$valueField])) === "OTHER"){
						$other = "\t<option value='".$row[$idField]."' ".$selected." style=\"background-color:#efefef\">".$row[$valueField]."</option>\n";
					}else{
						$retValue .= "\t<option value='".$row[$idField]."' ".$selected.">".$row[$valueField]."</option>\n";
					}
				}
				$retValue .= $other;
			}else{
				if (empty($value)){
					return ;
				}
				$sql .= " WHERE ".$idField." = ". $value.";";

				$a = $this->db->select($sql);
				//$a = $stmt->fetch();
				$retValue = $a[0][$valueField];
			}

			return $retValue;
	}
	
	public function input($type, $name, $value=NULL){
		
		switch(strtoupper($type)){
				CASE 'TEXT' 	: 	$retValue = "<input type='text' name='".$name."' id='".$name."' value=\"".$value."\">"; BREAK;
				CASE 'PASSWORD' :	$retValue = "<input type='password' name='".$name."'  id='".$name."' value=\"".$value."\">"; BREAK;
				CASE 'HIDDEN' 	: 	$retValue = "<input type='hidden' name='".$name."' id='".$name."' value=\"".$value."\">"; BREAK;
				CASE 'TEXTAREA' : 	$retValue = "<textarea name='".$name."' id='".$name."'>".$value."</textarea>"; BREAK;
				CASE 'CHECKBOX' : 	$checked = (strtoupper($value) == 'YES')? "checked ='checked'" : '';
									$retValue = "<input type='checkbox' name='".$name."' id='".$name."' value='1' ".$checked." />"; BREAK;
				CASE 'RADIO' : 		$retValue = "<input type='radio' name='".$name."' id='".$name."' value='".$value."' />"; BREAK;
			}
			return $retValue;
	}
	
	private function strip_tags($string){
		preg_match_all("({\*(.*)\*})siU", $string, $matching_data);
		return $string = str_replace($matching_data[0], "", $string);

	}
	
	public function externalLink($link){
		if(!empty($link)){
			return "<a href=\"".$link."\" target=\"_blank\">".$link."</a>";
		}
		return;
	} 
	
	
}