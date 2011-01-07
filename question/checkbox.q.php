<?php
foreach(explode(',',$field[3]) AS $list){


						if(preg_match( "/^F\|/", $list)){
							$function=str_replace("F|",'',$list);
							list($list, $values)=explode('=', $function);
							$list_function = $list;
							$file .= $list_function($values);
						}else{
							//Nulled Errors as $checked can be Blank in this case
							$listofcheckboxes = explode(',',$list);
							foreach($listofcheckboxes AS $checkboxDetails){
								@list($name,$value,$checked,$idName) = explode('|',$checkboxDetails);
								if ($checked == 1){
									$checked = "checked";
								}
								if (!empty($idName)){
									$func_id = " id=\"".$idName."\"";
								}
								$file .= '<input type="checkbox" name="'.$field[1].'[]"'.$func_id.' value="'.$value.'" '.$funcVars.' '.$checked.' '.$valid_str.' '.$this->autoRefreshcheckboxs.'>'.$name.'<br>';
							}
						}
					}
?>