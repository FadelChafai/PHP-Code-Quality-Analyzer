<!-- 
/**             
*        DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
*                     Version 2015
*
*  Copyright (C) 2015 Fadel Chafai <fadelchafai@gmail.com>
*
*  Everyone is permitted to copy and distribute verbatim or modified
*  copies of this license document, and changing it is allowed as long
*  as the name is changed.
*
*             DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
*    TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
*   0. You just DO WHAT THE FUCK YOU WANT TO.
*/
 -->
<!DOCTYPE html>
<html lang="en">
<head>
<title>PHP Coding Standards</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="assert/css/bootstrap.min.css">
<link rel="stylesheet" href="assert/css/bootswatch.min.css">
<script src="assert/js/jquery-1.10.2.min.js"></script>
<script src="assert/js/bootstrap.min.js"></script>
<style>
.btnside button{ width:  100%;}
</style>
</head>
<body>
<div class="container">
 <div class="page-header" id="banner">
   <div class="row">
     <div class="col-lg-12">
       <h1>PHP Coding Standards</h1>
       <p class="lead">PHP Mess Detector, PHP Code Sniffer et PHP Coding Standards Fixer ...</p>
     </div>
   </div>
 </div>
 <div class="container">
<div class="row">
<div id="msg"></div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
      <form class="navbar-form" role="search">       
          <input type="text" class="form-control" placeholder="File or Directory Path (Relative to your project root directory)" id="filepath" style="width: 80%" 
          value="" />
          <button type="button" class="btn btn-default" id="checkfile">Check path</button>
      </form>
      <div class="form-group">
      <label class="col-lg-1 control-label">Standards</label>
      <div class="col-lg-11">
        <div class="radio">
          <label>
            <input type="radio" name="standards" id="standards1" value="PSR2" checked="checked" /> PSR-2
          </label>
          <label>
            <input type="radio" name="standards" id="standards2" value="PSR1" > PSR-1
          </label>
          <label>
            <input type="radio" name="standards" id="standards3" value="Zend" > Zend
          </label>
          <label>
            <input type="radio" name="standards" id="standards4" value="PEAR" > PEAR
          </label>
          <label>
            <input type="radio" name="standards" id="standards5" value="Squiz" > Squiz
          </label>
          <label>
            <input type="radio" name="standards" id="standards6" value="PHPCS" > PHPCS
          </label>
          
        </div>
     
      </div>
    </div>
  </div>
</nav>
<div class="col-md-2 btnside">
<button class="btn btn-primary checker" data-type="phpmd">PHP Mess Detector</button>
<br /><br />
<button class="btn btn-success checker" data-type="phpcs">PHP Code Sniffer</button>
<br /><br />
<button class="btn btn-warning checker" data-type="phpcsfixer">PHP Standards Fixer</button>
<br /><br />
<button class="btn btn-warning checker" data-type="phpmetrics">PHP Metrics</button>
</div>
<div class="col-md-10">
<div id="rst"></div>
</div>
</div>
</div>

<footer>
        <div class="row">
          <div class="col-lg-12">

            <ul class="list-unstyled">
              <li class="pull-left"><a href="http://phpmd.org/">PHPMD</a></li>
              <li class="pull-left"><a href="https://github.com/squizlabs/PHP_CodeSniffer">PHPCS</a></li>
              <li class="pull-left"><a href="http://cs.sensiolabs.org/">PHP CS FIXER</a></li>
              <li class="pull-left"><a href="http://www.phpmetrics.org/">PHP METRICS</a></li>
            </ul>
             
          </div>
        </div>

      </footer>
</div>
<script>
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

	console.log('Bzi9');
	
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

</script>    
</body>
</html>