"use strict";

$(document).ready(function() {
	$("#checkfile" ).click(function() { checkFile(); });
	
	$(".checker" ).click(function() { 
		if(checkFile()){
			$("#rst").html('<center><img src="assert/img/loader-1.gif"></center>');
    		$.get( "http.php", { file: $("#filepath").val(), checker : $(this).data('type'), stdr : $('input[name=standards]:checked').val()} )
    		  .done(function( data ) {
    			  $("#rst").html( data );
    		}); 
		}
	});

	$('#filepath').keypress(function (e) {
		 var key = e.which;
		 if(key == 13)  // the enter key code
		  {
			checkFile(); 
		    return false;  
		  }
		}); 
});

function checkFile(){

	console.log('Bzi9'); // WTF is this shit
	
	$("#msg").html('');
	$("#rst").html('');
	
	if ($('#filepath').val() == ""){
		$("#msg").html( '<div class="alert alert-danger" role="alert"><b>Ooops :</b> Empty file path :( </div>' );
		return false;
	}
	$.get( "http.php", { file: $("#filepath").val()} )
	  .done(function( data ) {
		  $("#msg").html( data );
	});

	return true;
}
