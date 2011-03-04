$(document).ready(function(){
	
	var config = {
			toolbar:'MyToolbar' 
	};
	$('textarea#discription').ckeditor(config);	
	$('textarea#requirments').ckeditor(config);	
	
	$("input#title").hint('Create New title');
	$("input#start_date").datefield();
	$("input#end_date").datefield();
	
	
});

function generalLink(page){
	var action = 'action='+$('#action').val();
	var id = '&id='+$('#id').val();
	//document.location.href = 'http://example.com/q=a';
	document.location.href = page+'?'+action+id;
	return false; 
}


jQuery.fn.isDate = function(dateStr) {
	var datePat = /^(\d{1,2})(\/)(\d{1,2})(\/)(\d{4})$/;
	var matchArray = dateStr.match(datePat); // is the format ok?

	if (matchArray == null) {
		alert("Please enter date as either mm/dd/yyyy.");
		return false;
	}

	month = matchArray[3]; // p@rse date into variables
	day = matchArray[1];
	year = matchArray[5];

	if (month < 1 || month > 12) { // check month range
		alert("Month must be between 1 and 12.");
		return false;
	}

	if (day < 1 || day > 31) {
		alert("Day must be between 1 and 31.");
		return false;
	}

	if ((month==4 || month==6 || month==9 || month==11) && day==31) {
		alert("Month "+month+" doesn`t have 31 days!")
		return false;
	}

	if (month == 2) { // check for february 29th
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day > 29 || (day==29 && !isleap)) {
			alert("February " + year + " doesn`t have " + day + " days!");
			return false;
		}
	}
	
	return true; // date is valid
}

jQuery.fn.money = function() {
	  this.live('keypress', function(e) {

			var keys=new Array(8,46,48,49,50,51,52,53,54,55,56,57,0);
			if(jQuery.inArray(e.which, keys) != '-1'){
				return true;
			}
			
			return false;
	  });
};

jQuery.fn.datefield = function() {
	 var selector = this.selector;

	 this.live('click', function() {
		$(selector).datepicker({
			showOn:'focus',
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd/mm/yy'}).focus();
	
	});
}

jQuery.fn.year = function() {
	  var selector = this.selector;
	  this.live('keypress', function(e) {
			var keys=new Array(8,48,49,50,51,52,53,54,55,56,57,0);
			
			if(jQuery.inArray(e.which, keys) != '-1'){
				if($(selector).val().length < 4 ){
					return true;
				}else if(e.which == 8){
					return true;
				}else if(e.which == 0){
					return true;
				}
			}
			
			return false;
	  });
}

jQuery.fn.forcode = function() {
	  var selector = this.selector;
	  this.live('keypress', function(e) {
			var keys=new Array(8,48,49,50,51,52,53,54,55,56,57,0);
			
			if(jQuery.inArray(e.which, keys) != '-1'){
				if($(selector).val().length < 6 ){
					return true;
				}else if(e.which == 8){
					return true;
				}else if(e.which == 0){
					return true;
				}
			}
			
			return false;
	  });
}

jQuery.forcodeClean = function(forcode) {
		if(forcode.length == 1){
			return '';
		}else if(forcode.length == 3){
			return forcode.substring(0,2);
		}else if(forcode.length == 5){
			return forcode.substring(0,4);
		}else{
			return forcode.substring(0,6);
		}
}

jQuery.fn.number = function() {
	  var selector = this.selector;
	  this.live('keypress', function(e) {
			var keys=new Array(8,48,49,50,51,52,53,54,55,56,57,0);

			if(jQuery.inArray(e.which, keys) != '-1'){
				return true;
			}
			return false;
	  });
}

jQuery.IsEmpty = function(str) {
	 if ((str.length==0) || (str== null) || (str=='undefined') || (str == 0) ) {
	     return true;
	 }else{ 
		 return false; 
	 }
}

jQuery.URL = function(name) {
	
	var params = new Array();
	paramsRaw = (document.location.href.split("?", 2)[1] || "").split("#")[0].split("&") || [];
	for(var i = 0; i< paramsRaw.length; i++){
		var single = paramsRaw[i].split("=");
		if(single[0])
			 params[single[0]] = unescape(single[1]);
	}

	return params[name] || "";

}

jQuery.fn.hint = function (title, blurClass) {
  if (!blurClass) { 
    blurClass = 'blur';
  }

  return this.each(function () {
    // get jQuery version of 'this'
    var $input = jQuery(this),

    // capture the rest of the variable to allow for reuse
      //title = title,
      $form = jQuery(this.form),
      $win = jQuery(window);

    function remove() {
      if ($input.val() === title && $input.hasClass(blurClass)) {
        $input.val('').removeClass(blurClass);
      }
    }

    // only apply logic if the element has the attribute
    if (title) { 
      // on blur, set value to title attr if text is blank
      $input.blur(function () {
        if (this.value === '') {
          $input.addClass(blurClass);
          $input.val(title);
        }
      }).focus(remove).blur(); // now change all inputs to title

      // clear the pre-defined text when form is submitted
      $form.submit(remove);
      $win.unload(remove); // handles Firefox's autocomplete
    }
  });
};