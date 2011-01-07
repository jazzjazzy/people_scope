<?php
list($start, $end) = explode("|",$field[3]);

					if(preg_match( "/^F\|/", $field[4])){
						$list=str_replace("F|",'',$field[4]);
						$list_function = $list;
						$file .= $list_function($field[1]."__from", $start, $valid_str.$this->autoRefresh);
						$file .= " - ";
						$file .= $list_function($field[1]."__to", $end, $valid_str.$this->autoRefresh);
					}else{
						//TODO: NEED TO SETUP STANDARD RANGE
						$file .= create_date_field($field[1]."__from", $start) ." - ". create_date_field($field[1]."__from", $end);
					}
?>