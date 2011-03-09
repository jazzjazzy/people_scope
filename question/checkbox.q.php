<?php
//check for file if ajax we are one dir down, if not we are at the root
if(is_file('../config/config.php')){
	require_once '../config/config.php';
}else{
	require_once 'config/config.php';
}


$action = (isset($_REQUEST['action']))?$_REQUEST['action']:'';

$checkbox = new checkbox();

switch($action){
	case 'add-fields' : 
			$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
			echo $checkbox->addFields($id);
			break;
	case 'add-tracker' :
			$id =(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
			echo $checkbox->addTracker($id);
}

class checkbox{
	
	var $template;
	
	public function __construct(){
		
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		
		$this->template = new template('blank');
	}	

	function create($options=NULL, $label=NULL){
		
		@extract($options); //extract out from the array and convert them to vars  
	
		$this->template->page('question-list.tpl.html');
		
		$this->template->assign('css', "class=\"".@$cssClass.'"');
		$this->template->assign('style', "style=\"".@$cssStyle.'"');
		
		$value =''; 
		if(isset($_SESSION['Question_Details']['values'])){
			$value .= $this->addFields();
		}
		
		$this->template->assign('script', $this->script());
		$this->template->assign('label', $label);
		$this->template->assign('value', $this->getValues());
		$this->template->assign('edit', $this->edit(true));
		$this->template->assign('setting', $this->setting($options));
		
			
		return $this->template->fetch();	
	}
	
	function display($lable ,$field, $qid){
		
		$file =$lable."<br />";
		
		foreach($field AS $checkboxDetails){	
			$file .= '<input type="checkbox" name="q['.$qid.'][]" value="'.$checkboxDetails['multi_id'].'">'.$checkboxDetails['label'].'<br>';
		}

		return $file;
	}
	
	function edit($isCreate= false){
		
		$html = '';
		
		if ($isCreate){
			$html = '<span style="float:left"><input type="text" value="" id="add-field" style="width:300px";></span><span class="button" id="add-field-button">Add</span>
			<br /><br />
			<br />
			<br />';
			
		}
		$html .= '<div id="field-list">';
		if(isset($_SESSION['Question_Details']['values'])){
			$html.= $this->addFields();
		}else{
			$html.='No Fields';
		}
		
		$html.='</div>';
		return $html;
	
	}
	
	function setting($options=NULL){
	
		$html = "
		Is this field required: 
		<input type=\"radio\" value=\"yes\" name=\"required\">yes
		<input type=\"radio\" value=\"no\" name=\"required\" checked=checked>no
		<br /><br />
		CheckBox position: 
		<input type=\"radio\" value=\"BEFORE\" name=\"position\" checked=checked>Before
		<input type=\"radio\" value=\"AFTER\" name=\"position\">After
		<br /><br />
		Output running 
		<input type=\"radio\" value=\"TRUE\" name=\"horizontal\">Horizontal
		<input type=\"radio\" value=\"FALSE\" name=\"horizontal\" checked=checked>Vertical
		<br /><br />
		Tag Padding width: <input type=\"text\" id=\"tag-width\" value=\"\" name=\"options[padding]\" />
		<div id=\"slider-tag\" style=\"width:200px\"></div>
		<br /><br />
		Tag Font Size : <input type=\"text\" id=\"tag-size\" value=\"\" name=\"options[padding]\" />
		<div id=\"slider-tagsize\" style=\"width:200px\"></div>
		<br /><br />
		Add css class tag: <input type=\"text\" id=\"css-class\" value=\"".@$options['cssClass']."\" name=\"options[cssClass]\" />
		<br /><br />
		Add Direct styles: <textarea type=\"text\" id=\"css-style\" name=\"options[cssStyle]\">".@$options['cssStyle']."</textarea>";
		
		return $html;
		
	}
	
	function getValues($fields=NULL, $postion="AFTER", $horizontal=false ){
		
		
		if(!empty($fields)){
			$fieldArray = explode(',', $fields);
			foreach($fieldArray AS $key=>$value){
				$_SESSION['Question_Details']['values'][]['value'] = trim($value);
			}
		}
		
		$float='';
		if($horizontal){
			$float=" style=\"float:left\"";
		}
		
		$html = '';
		if(isset($_SESSION['Question_Details']['values'])){
		foreach($_SESSION['Question_Details']['values'] AS $key=>$value){
			$value['value'] = trim($value['value']);
			$html .= "<div$float>";
			if($postion == "AFTER"){
				$html .= "<input type=\"checkbox\" name=\"".$value['value']."\" id=\"".$value['value']."\"><label for=\"".$value['value']."\">".$value['value']."</label>\n";
			}else{
				$html .= "<label for=\"".$value['value']."\">".$value['value']."</label><input type=\"checkbox\" name=\"".$value['value']."\" id=\"".$value['value']."\">\n";
			}
			$html .= "</div>";
		}
		}

		return $html;
	}
	
	
	function addFields($fields=NULL){
		
		if(!empty($fields)){
			$fieldArray = explode(',', $fields);
			foreach($fieldArray AS $key=>$value){
				$_SESSION['Question_Details']['values'][]['value'] = trim($value);
			}
		}
		
		$html = '<div style="float:left;width:100px">List Entries</div><div style="float:left;width:10px">Tracking</div><br class="clear">';
		$html .= '<ul class="selection-list"  style="list-style-type:none;padding:0px">';
		foreach($_SESSION['Question_Details']['values'] AS $key=>$value){
			$html .= '<li><div style="float:left;width:100px">'.trim($value['value']).'</div><div style="float:left;width:10px"><input type="checkbox" class="tracker" id="'.$key.'"/></div><br class="clear"></li>';
		}
		$html .= '<ul>';
		return $html;
	}

	function addTracker($field){
		$_SESSION['Question_Details']['values'][$field]['tracker'] = true;
		return ($_SESSION['Question_Details']['values'][$field]['tracker'] == true)? true : false;
	}
	
	function script(){
		
		$html = <<< edo
		$(document).ready(function(){
				
				$("#add-field-button").click(function(){
					var field = $("#add-field").val();
					$.post("question/checkbox.q.php?action=add-fields&id="+field, function(result){
						$("#field-list").html(result);
						$(".selection-list").sortable({axis: "y"});
						$("#add-field").val("");
						$("#example div#value").exampleVal();			
					});
				});
				
				$(".tracker").live("click", function(){
					var field = $(this).attr("id");
					
					if($(this).is(":checked")){
						var type = "add";
					}else{
						var type = "remove";
					}
					$.post("question/checkbox.q.php?action="+type+"-tracker&id="+field, function(result){
						
					})
				});
				
				$(".selection-list").sortable({axis: "y"});
				
				$("input[name=required]").click(function(){
					var checked = $("input[name=required]:checked").val();
					if(checked == "yes"){
						$("#example div#label").append('<span id="req">*</span>');
					}else{
						$("#req").remove();
					}
				});
				
				$("#css-class").change(function(){
					$("#example div#label").addClass($("#css-class").val());
					$("#example div#value").exampleVal();
				});
				
				$("#css-style").change(function(){
	
					var cssElements = new Array();
					var styles = $("#css-style").val();
					var cssString = styles.split(";");

					for(var i in cssString){
						var temp = cssString[i];
						var element = temp.split(":");
						cssElements[element[0]] = element[1];
					}
					$("#example div#label").css(cssElements);
				});

				$("#tag-width").keyup(function(e){
					$("#example div#value").exampleVal();	
				});
				
				$("input[name=position]").click(function(){
					$("#example div#value").exampleVal();	
				});

				
				$( "#slider-tag" ).slider({
					value:0,
					min: 14,
					max: 200,
					step: 1,
					slide: function( event, ui ) {
						$("#tag-width").val( ui.value );
						$("#example div#value").exampleVal();	
					}
				});

				
				$( "#slider-tagsize" ).slider({
					value:0,
					min: 14,
					max: 35,
					step: 1,
					slide: function( event, ui ) {
						$("#tag-size").val( ui.value );
						$("#example div#value").exampleVal();	
					}
				});

				$("input[name=horizontal]").click(function(){
					var checked = $("input[name=horizontal]:checked").val();
					var pos = $("input[name=position]:checked").val();
					if(checked == 'TRUE'){
						$("#example div#value div").css('float','left');
					}else{
						$("#example div#value div").css('float','none');
					}	
				});
			});

			jQuery.fn.exampleVal = function(){
				var select="";
				
				$(".selection-list>li").each(function(index) {
					var name= $(this).text();
					
					if($("input[name=position]:checked").val() == "AFTER"){
						select += "<div><span><label for=\""+name+"\">"+name+"</label></span><span><input type=\"checkbox\" name=\""+name+"\" id=\""+name+"\"></span></div><div />";
					}else{
						select += "<span><input type=\"checkbox\" name=\""+name+"\" id=\""+name+"\"></span><span><label for=\""+name+"\">"+name+"</label></span><div />";
					}
				});
				select += "<div class=\"clear\" />";
				
				$(this).html(select);

				var tag = $("#tag-width").val();
				
				if(tag == ''){
					$('#example div#value label').css({'float':'none', 'width':'none'});
				}else{
					if($("input[name=position]:checked").val() == "AFTER"){
						$('#example div#value label').css({'float':'left', 'width':tag+'px'});
					}else{
						$('#example div#value span').css({'float':'left', 'width':tag+'px'});
					}
				}


				var fontsize = $("#tag-size").val();
				
				if(fontsize == ''){
					$('#example div#value label').css({'font-size': 'none'});;
				}else{
					$('#example div#value label').css({'font-size': fontsize+'px'});
				}

				if($("input[name=horizontal]:checked").val() == 'TRUE'){
					$("#example div#value span").css('float','left');
				}
				
				var cssElements = new Array();
				var styles = $("#css-style").val();
				var cssString = styles.split(";");

				for(var i in cssString){
					var temp = cssString[i];
					var element = temp.split(":");
					cssElements[element[0]] = element[1];
				}
				$("#example div#label").css(cssElements);
				$("#example div#label").addClass($("#css-class").val());
			};
edo;
		return $html; 
		
	}
	
	function saveValues($qid, $appid, $valueList ){

		foreach($valueList AS $value){
			$list[] = "(".$appid.",".$qid.",".$value.")";
		}
		
		$sql = "INSERT INTO applications_question (application_id, question_id, multi_id) VALUES ";
		$sql .= implode(",", $list);

		try{
			$application_id = $this->db->insert($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}
	}
}
?>