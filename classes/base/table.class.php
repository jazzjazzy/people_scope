<?php
/**
 * Template Class, 
 * <br />
 * This class is used to take a input to generate templates<br /> 
 * 
 * Example:<br />
 * <br />
 * $template = new template('template/index.html');<br />
 * <br />
 * $template->assign('var1', 'Employment list');<br />
 * $template->assignArray(array{'var2'=>'John', 'var3'=>'mary', 'var4'=>'<strong>frank</strong>'});<br />
 * <br />
 * $ArrayVars[0]['name'] = 'john';<br />
 * $ArrayVars[0]['age'] = '14';<br />
 * $ArrayVars[1]['name'] = 'mary';<br />
 * $ArrayVars[1]['age'] = '42';<br />
 * $ArrayVars[2]['name'] = 'frank';<br />
 * $ArrayVars[2]['age'] = '98';<br />
 * <br />
 * $template->assignRepeat('agelist', $ArrayVars);<br />
 * $template->replace('../', '../../');<br />
 * <br />
 * $template->display();<br />
 * <br />
 *  
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 * @subpackage Base
 */


class table{

	private $identifier;
	private $headerArray = array();
	private $filterArray = array();
	private $removeColumnArray = array();
	private $columnsWidth = array();
	private $id;
	private $name = 'list';
	private $basePage = NULL;
	private $row_class_name;
	private $row_class_field_name;
	private $link_action = 'show';
	private $link_field;
	private $footer;
	private $nolink = false;
	private $rowsOnly = false;
	private $SEOurl=SEO_LINK;
	

	function __construct($id = NULL){
		$this->id = (!empty($id))? $id : NULL;
		//by default get the current file name
		$break = explode('/',  $_SERVER['PHP_SELF']);
		$pfile = $break[count($break) - 1]; 
		//strip off the .php and store it 
		$this->basePage = str_ireplace('.php', '', $pfile);
		$this->identifier_link_page = $this->basePage;
		$this->SEOurl = (SEO_LINK===true) ? true : false;
	}
	
	function __destruct(){

	}
	
	public function setHeader($headerArray){
		$this->headerArray=$headerArray;
	}
	
	public function setRowsOnly(){
		$this->rowsOnly = true;
	}
	
	public function setFooter($field){
		$this->footer=$field;
	}
	
	public function setFilter($filterArray){
		$this->filterArray=$filterArray;
	}
	
	public function removeColumn($columnArray){
		$this->removeColumnArray=$columnArray;
	}
	
	public function setColumnsWidth($columnWidthArray){
		$this->columnsWidth=$columnWidthArray;
	}
	
	public function setColumnsClass($columnClassArray){
		$this->columnsWidth=$columnClassArray;
	}
	
	public function setIdentifier($field, $no_link=false){
		$this->identifier=$field;
		
		//if not link is true, then only generate table with the identifier as the ID='' 
		if($no_link){
			$this->nolink=true;
		}
	}
	
	public function setLinkAction($action){
		$this->link_action = $action;
	}
	
	public function setLinkField($action){
		$this->link_field = $action;
	}
	
	public function setIdentifierPage($page){
		if($this->SEOurl){
			$page = (str_ireplace('/', '', str_ireplace('.php', '', $basepage)));
		}
		$this->identifier_link_page = $page;
	}
	
	public function setTableName($name){
		$this->name = $name;
	}
	
	public function setBasePage($basepage){
		$this->basePage = (str_ireplace('/', '', str_ireplace('.php', '', $basepage)));
	}

	public function setPrimaryId($id){
		$this->id = (!empty($id))? $id : NULL;
	}
	
	/**
	 * Set the Class name for a Row in the table
	 * @param $name
	 * @return void
	 */
	public function setRowClassName($name){
		$this->row_class_name = (!empty($name))? $name : NULL;
	}
	
	public function setRowClassFieldName($name){
		$this->row_class_field_name = (!empty($name))? $name : NULL;
	}
	
	public function genterateDisplayTable($content){
		//set vars
		$id = '';
		$column = '';
		$filter = '';
		$columnVal = '';
		$filterVal = '';
		//copy of main array for filter
		$content2 = $content;
		$tr = '';
		$td = '';
		$link = '';
		$CSS_id='';
		$cWidth = '';
		
		//return if nothing to build 
		/*if (count($content) == 0 || $content == false){
			return "None"; 
		}*/
		//cycle echo throught the records
		if($content > 0){
				foreach($content as $columnKey=>$val)
				{		
						
						// check if we had made a list for the header
				    	if(!$column){
							//cycle thought each field list, this being the first need to also create header 
							$countColumn = 0;
				    		foreach($val AS $key=>$value){
				    				
				    				//if(isset($this->row_class_field_name)){
										if($key == $this->row_class_field_name){
											$className = $value." ".$this->row_class_name;
										}else{
											$className = $this->row_class_name;
										}
										
									//}
				    				
									//is there a need to reset the header names 
				 				    if (is_array($this->headerArray)){
										//if current header is in headerArray change the display header name 
										if(array_key_exists($key, $this->headerArray)){
											$columnVal = $this->getOrderType($this->headerArray[$key], $key);
										}else{
											$columnVal = $this->getOrderType($key, $key); //just add header, no change  
										}
									}else{								
											$columnVal = $this->getOrderType($key, $key); //just add header, no change  
									}
									
									//if the identifier used for the linking to a page, e.g primary id   
									if($key == $this->identifier){
										$id = $val[$key];
										$page = (isset($this->identifier_link_page))? $this->identifier_link_page : $_SERVER['PHP_SELF'] ;
										if($this->nolink){
											$link = "id=".$id;
										}else{
											if(isset($this->link_field)){
												$action = $val[$this->link_field];
											}elseif(isset($this->link_action)){
												$action = $this->link_action;
											}else{
												$action = "show";
											}
											if($this->SEOurl){
												$link = "onclick=\"location.href='".$page."/".$action."/".$id."'\" id=".$id;
											}else{
												$action = "action=".$action;
												$link = "onclick=\"location.href='".$page.".php?".$action."&id=".$id."'\" id=".$id;
											}
											//$link = "onclick=\"location.href='".$page."?".$action."&id=".$id."'\" id=".$id;
										}
									}
									if(!in_array($key, $this->removeColumnArray)){
										if(!empty($this->columnsWidth[$countColumn])){
											$cWidth = ' width="'.$this->columnsWidth[$countColumn].'"';
										}
										$column .="<th$cWidth>".$columnVal."</th>"; // append to header
										$td .= $this->buildTd($key, $value);
									}
									
									// is there a need to a filter row 
									if (is_array($this->filterArray) || !$this->rowsOnly){
										//if current header is in filterArray change then add the filter to the filter row
										if(array_key_exists($key, $this->filterArray)){
											$filterVal = $this->getFilterType($this->filterArray[$key], $key, $content2); // format filter type 
										}  
										if(!in_array($key, $this->removeColumnArray)){
											if($countColumn == 0){
												$primaryid = (!empty($this->id))?"<input type='hidden' name='id' value='".$this->id."'>":'';
												$filter .="<td NOWRAP>".$primaryid.$filterVal."</td>"; // append then Field to filter Row
											}else{
												$filter .="<td NOWRAP>".$filterVal."</td>"; // append then Field to filter Row
											}
											$countColumn++;
										}
				    			    }
				    			    
							}
				    	}else{
							//cycle thought each field in row 
							
				    		foreach($val AS $key=>$value){
				 					if(!in_array($key, $this->removeColumnArray)){
										$td .= $this->buildTd($key, $value);
									}
									
				    				//if(isset($this->row_class_field_name)){
										if($key == $this->row_class_field_name){
											$className = $value." ".$this->row_class_name;
										}else{
											$className = $this->row_class_name;
										}
										
									//}
									
									//if the identifier used for the linking to a page, e.g primary id   
									if($key == $this->identifier){
										$id = $val[$key];
										$page = (isset($this->identifier_link_page))? $this->identifier_link_page : $_SERVER['PHP_SELF'] ;
										if($this->nolink){
											$link = "id=".$id;
										}else{
											if(isset($this->link_field)){
												$action = "action=".$val[$this->link_field];
											}elseif(isset($this->link_action)){
												$action = "action=".$this->link_action;
											}else{
												$action = "action=show";
											}
											
											$link = "onclick=\"location.href='".$page.".php?".$action."&id=".$id."'\" id=".$id;
										}
									}
				    		}
				    	}
						$tr .= "<tr ".$link ." class=\"".$className." row\">".$td."</tr>\n";// wrap in table row  and append 
				    	$td = '';
				}
		}
		$footer = (isset($this->footer))? "<TFOOT>".$this->footer."</TFOOT>" : '';
		unset($this->footer);
		
		if($this->rowsOnly){
			$table = $tr;
		}else{
			$table = "<table class=\"table_lists\" cellpadding=\"0\" cellspacing=\"0\" id=\"".$this->name."\" page=\"".$this->basePage."\">\n\t$footer<tr class=\"header\">".$column."<tr><tr class=\"filter noprint\">".$filter."</tr>\n\t".$tr."</table>"; //build table 
		}
		
		return $table; 
	} 
	
	private function getFilterType($type, $key, $content = NULL){
		switch($type){
			case 'TEXT' : 
				$field = "<input type=\"text\" name=\"flt_".$key."\" />"; break;
			case 'COMPILED' : 
			case 'COMPLIED' : 
				//lets get all the values for the content list
				foreach($content AS $contentkey => $contentVal){
					$contentArray[] = $contentVal[$key];
				}
				//compact and sort 
				$contentOrder = array_unique($contentArray);
				sort($contentOrder);
				//make the options list 
				$optionList = "<option value=\"NULL\"></option>";
				foreach($contentOrder AS $val){
					$optionList .= "<option value=\"".$val."\">".$val."</option>";
				}
				$field = "<SELECT  name=\"flt_sel_".$key."\">".$optionList."</SELECT>"; break;
			case 'VALUE' : 
				$field = "<SELECT name=\"dir_".$key."\" >
				<option value=\"eq\" selected>=</option>
				<option value=\"greater\">>=</option>
				<option value=\"less\"><=</option>
				</SELECT>
				<input type=\"text\" name=\"flt_".$key."\" size=\"2\" />"; break;
			case 'MONEY' : 
				$field = "<SELECT name=\"dir_".$key."\" >
				<option value=\"eq\" selected>=</option>
				<option value=\"greater\">>=</option>
				<option value=\"less\"><=</option>
				</SELECT>
				<input type=\"text\" name=\"flt_".$key."\" size=\"2\" />"; break;
			default: $field = '';
		}
		
		return $field;
	}
	
	
	private function getOrderType($type, $key){
		
		$field = "<div id=\"".$key."\">".$type."</div>";
		
		return $field;
	}
	
	private function buildTd($key, $value){
		
		if(array_key_exists($key, $this->filterArray)){
			
			$type = $this->filterArray[$key];
			switch($type){
				case 'VALUE' : $td ="<td style=\"text-align:center\">".$value."</td>"; break;// append then Field to Row
				case 'MONEY' : $td ="<td style=\"text-align:center\">".number_format($value)."</td>"; break;// append then Field to Row
				default : $td ="<td>".$value."</td>";
			}

		}else{
			$value2 = str_replace(',', '',$value);
			if(is_numeric($value2)){
				$td ="<td style=\"text-align:center\">".$value."</td>";
			}else{
				$td ="<td>".$value."</td>"; // append then Field to Row
			}
		}
		
		return $td;
	}
	
	public function buildWhereArrayFromRequest($full_like=NULL){
		
		//set vars 
		$filter = false;
		
		foreach($_REQUEST AS $key=>$val){
			if (substr($key, 0 ,4) == 'flt_'){
				
				$field = str_replace(substr($key, 0 ,4), '', $key);
	
				if(isset($_REQUEST['dir_' . $field])){
	
					switch(trim($_REQUEST['dir_' .$field])){
						case 'greater' : $direction = ' >= '; break;
						case 'less' : $direction = ' <= '; break;
						case 'eq' : $direction = ' = '; break;
						default : $direction = ' = '; break;
					}
					$filter[] = $field  .$direction. $val;
				}else{
					if (substr($field, 0 ,4) == 'sel_'){
						if ($val != 'NULL'  && !empty($val)){
							$filter[] = str_replace(substr($field, 0 ,4), '', $field) ." = '". $val."'";
						}
					}else{
						if ($val != 'NULL'  && !empty($val)){
							if($full_like){
								$filter[] = $field  ." LIKE '%". $val."%'";
							}else{
								$filter[] = $field  ." LIKE '". $val."%'";
							}
						}
					}
				}
			}
		}

		return $filter;
	}
}