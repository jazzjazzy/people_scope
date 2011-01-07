<?php
$file = "<select name=\"".$field[1]."\" ".$funcVars." ".$valid_str.$this->autoRefresh.">";
					foreach(explode(',',$field[3]) AS $list){
						if(preg_match( "/^F\|/", $list)){
							$function=str_replace("F|",'',$list);
							list($list, $values) = explode('=', $function);
							$list_function = $list;
							$file .= $list_function($values);
						}else{
							//nulled Error as value can be blank in this case
							@list($name,$value,$selected) = explode('|',$list);
							$file .= "<option";
							$file .= ($value)? ' value="'.$value.'"':'';
							if ($selected == 'select'){
									$file .= " selected";
							}
							$file .= ">".$name."</option>\n";

						}

					}
					$file .= "</select>";
?>