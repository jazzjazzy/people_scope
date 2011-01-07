<?php
if(preg_match( "/^F\|/", @$field[4])){
						$list=str_replace("F|",'',$field[4]);
						$list_function = $list;
						$fileList = $list_function($field[1], $field[3], $valid_str.$this->autoRefresh);
					}else{
						$fileList = create_date_field($field[1], $field[3]);
						$file = "<select name=\"".$field[1]."__day\" ".$funcVars." ".$valid_str.$this->autoRefresh.">";
						$file .= $fileList['day']."</select>";
						$file .= "<select name=\"".$field[1]."__month\" ".$funcVars." ".$valid_str.$this->autoRefresh.">";
						$file .= $fileList['month']."</select>";
						$file .= "<select name=\"".$field[1]."__year\" ".$funcVars." ".$valid_str.$this->autoRefresh.">";
						$file .= $fileList['year']."</select>";
					}
?>