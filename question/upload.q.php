<?php

function display($field,$funcVars, $valid_str){
	$file='<input type="hidden" name="MAX_FILE_SIZE" id="edit-MAX-FILE-SIZE" value="2000000"   />
	<div class="clearer"></div><div id="file_container"><div class="form-item">
	
	 <input type="file" name="files['.$field[1].']"  class="form-file" id="edit-resume" size=""  validitycheck="'.$field[1].';'.$field[0].';required;wordorpdf;0;-1"/>
	
	<!-- <div class="description">Please attach your resume in PDF or Word format, including any relevant experience. Resumes over 2MB will not be accepted.</div>
	</div>-->
	</div><div class="clearer"></div><div id="upload_instructions">* PDF or Word format only. Max 2MB</div>';
	
	return $file;
}
?>