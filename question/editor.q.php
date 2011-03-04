<?php
function display($field,$funcVars, $valid_str){
	foreach(explode(',',$field[3]) AS $list){

		if(preg_match( "/^f\|/", $list)){
			//TODO: add the use of function to this area
		}else{
			$value = $field[3] ;

			$value = str_replace("***", "\n", $value);
			$value = str_replace("*&*", ":", $value);

		}
	}

	if (!empty($field[4])){
		list ($fck_width, $fck_height) = explode('|', $field[4]);
		if (!is_numeric($fck_width) || !is_numeric($fck_height)){
			pp("hight or width is not numeric please reset",1);
		}
	}else{
		$fck_width = $this->fck_width;
		$fck_height = $this->fck_height;
	}
	//Nulled Errors as $field[1] can be Blank in this case
	$oFCKeditor = new FCKeditor($field[1]) ;
	$oFCKeditor->BasePath = SITE_ROOT.'assets/fckeditor/';
	if (!empty($this->fck_configUrl)){
		$oFCKeditor->Config['CustomConfigurationsPath'] = $this->fck_configUrl;
	}
	$oFCKeditor->Toolbar = 'Basic';
	$oFCKeditor->Value   = $value;
	$oFCKeditor->Width   = $fck_width ;
	$oFCKeditor->Height  = $fck_height ;

	$file .= $oFCKeditor->CreateHtml() ;
	return $file;
}
?>