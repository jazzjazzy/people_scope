<?php
foreach(explode(',',$field[3]) AS $list){
						if(preg_match( "/^f\|/", $list)){
							//TODO: add the use of function to this area
						}else{
							//Nulled Errors as $checked can be Blank in this case
							@list($name,$value,$checked) = explode('|',$list);
							if ($checked == 1){
								$checked = "checked";
							}
							$file .= '<input type="radio" name="'.$field[1].'" value="'.$value.'" '.$funcVars.' '.$checked.' validitycheck="" '.$this->autoRefreshcheckboxs.'>'.$name.'<br>';
						}
					}
?>