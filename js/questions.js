$(document).ready(function(){

	//$('#example').tabs();
	
	$(".question").sortable({
			axis: 'y',
			update: function(event, ui) { 
				var order = $(".question").sortable('serialize', {attribute:'id'});
				var pid = $('#pid').val(); 
				$.post("ajax/question.ajax.php?action=arrange-sort&id="+pid+"&"+order);
					ui.item.unbind("click");
					ui.item.one("click", function (event) { 
	                event.stopImmediatePropagation();
	                $(this).click(getQuestionDetails);
	            }); 
	            console.log("update"); 
			},
			delay: 30
	});
	
	$(".question").disableSelection();
	
	$('#close').live('click',function(){
		$(".question").sortable({disabled : false});
		$("#question-box").css({ display: "none"});
		$("#question-pointer").css({ display: "none"});
	});
	
	$(".question li").click(getQuestionDetails);
	
	$(".cancel-button").live("click", function(){
		$("#question-box").css({ display: "none", position: "static", marginLeft: 0, marginTop: 0});
		$("#question-pointer").css({ display: "none", position: "static", marginLeft: 0, marginTop: 0});
		$('#question-content').html("Closed");
	})
	
	$('.qedit').live('click',function(){
		$('#question-content').html("Loading....");
		var pid = $('#pid').val();
		var id_val= $(this).attr('id').split("-");
		var id = id_val[0];
		
		$('#question-content').load("ajax/question.ajax.php?action=edit-question&id="+id+"&pid="+pid);
	});
	
	$( ".pool" ).accordion();
	
	$(".pool-list").bind('dblclick', function(){
		var pid = $('#pid').val();
		var id_val= $(this).parents('li').attr('id').split("_");
		var id = id_val[1];
		var thisObj = this;
		
		if (window.getSelection)
	        window.getSelection().removeAllRanges();
	    else if (document.selection)
	        document.selection.empty();
		
		$.post("ajax/question.ajax.php?action=add-question&id="+id+"&pid="+pid, function(result){
			$(".question").append(result);
			$(".question li:last").click(getQuestionDetails);
			$(thisObj).parent(thisObj).fadeOut(500, function(){
				$(thisObj).parent(thisObj).remove();
			});
			
		});
	});
	
	$(".question-list").bind('dblclick', function(){
		var pid = $('#pid').val();
		var id_val= $(this).parents('li').attr('id').split("_");
		var id = id_val[1];
		var thisObj = this;
		
		if (window.getSelection)
	        window.getSelection().removeAllRanges();
	    else if (document.selection)
	        document.selection.empty();
		
		$.post("ajax/question.ajax.php?action=remove-question&id="+id+"&pid="+pid, function(result){
			$(".question").append(result);
			$(thisObj).parent(thisObj).fadeOut(500, function(){
				$(thisObj).parent(thisObj).remove();
			});
		});
	});
	
	
	$(".tracker").live("click", function(){
		var pid = $('#pid').val();
		var qid = $('#qid').val();
		var tid = $(this).attr('id');
		
		if ($(this).is(':checked')){
			$.post("ajax/question.ajax.php?action=set-tracking&qid="+qid+"&pid="+pid+"&tid="+tid, function(result){
				alert(result);
				if(result == "isTracking"){
					$("#q_"+qid).animate({backgroundColor:"#F58A0D"},"slow");
				}else if(result == "noTracking"){
					$("#q_"+qid).animate({backgroundColor:"#000000"},"slow");
				}else{
					alert('Error updating');
				}
			});
		}else{
			$.post("ajax/question.ajax.php?action=set-tracking&qid="+qid+"&pid="+pid+"&tid="+tid, function(result){
				if(result == "isTracking"){
					$("#q_"+qid).animate({backgroundColor:"#F58A0D"},"slow");
				}else if(result == "noTracking"){
					$("#q_"+qid).animate({backgroundColor:"#000000"},"slow");
				}else{
					alert('Error updating');
				}
			});
		}
	})
	
	/*************assign template +++++++++++++++++++++/
	 * 
	 */
	
	//$("#example div#value").exampleVal();
	
	$('#question-type').change(function(){
		var type = $(this).val();
		$.post("ajax/question.ajax.php?action=get-question-details&id="+type, function(result){
			$('#create-question').html(result);
			$("#example div#label").html($("textarea[name=label]").val());
			
		});
	});
	
	
	
	$("textarea[name=label]").keyup(function (e){
		if($("textarea[name=label]").val() != ""){
			
			$("#example div#label").html($.trim($("textarea[name=label]").val()));

			var checked = $('input[name=required]:checked').val();
			
			if(checked == "yes"){
				$('#example div#label').append('<span id="req">*</span>');
			}
		}
	});
	
	
	/*jQuery.fn.exampleVal = function() {
		var select="";
		$(".selection-list>li").each(function(index) {
			var name= $(this).text();
		    select += "<div>K<input type=\"checkbox\" name=\""+name+"\" id=\""+name+"\" style=\"width:10px\"><label for=\""+name+"\">"+name+"</label></div>\n";
		});
		$(this).html(select);
	};*/
});


var getQuestionDetails = function() {
	
		var pid = $('#pid').val();
		var id_val= $(this).attr('id').split("_");
		var id = id_val[1];
		
		var position = $(this).position();
		var qposition = $("#question-placer").position();
		
		
		var x = position.left + $(this).width();
		var temp  = position.top + ($(this).height()/2);
		
		//var imgHeight1 = $("#bee").height()/2;
		var imgHeight = $("#question-pointer").height();
		var y = temp - imgHeight;
		var y2 = temp-($("#question-box").height()/2);
		
		if(y2 < qposition.top){
			y2 = qposition.top;
		}
		
		$(".question").sortable({disabled : true});
			
		$("#question-box").css({ display: "block", position: "absolute", marginLeft: 0, marginTop: 0, top: y2, left: (x+16) });
		$("#question-pointer").css({ display: "block", position: "absolute", marginLeft: 0, marginTop: 0, top: (y+8), left: (x+10) });
		$('#question-content').html("Loading....");
		$('#question-content').load("ajax/question.ajax.php?action=show-question&id="+id+"&pid="+pid);
	
};
