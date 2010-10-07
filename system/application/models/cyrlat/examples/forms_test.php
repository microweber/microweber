<?php
if (!isset($_POST['convert'])){
	echo "
<html>
  <head><title>lat2cyr test form</title></head>
	<body>
	 <form action=forms_test.php method=POST>
	  <textarea name=input rows=10 cols=60>Pro Scheloch'</textarea><br>
	  <input name=how type=radio value=0 checked>lat2cyr test<br>
	  <input name=how type=radio value=1>cyr2lat test<br> 
	  <input type=submit name=convert value='GO!'>
	 </form>
	</body>
</html>";
exit;
}
require_once("../cyrlat.class.php");
$cyrlat=new CyrLat;
$input=$_POST['input'];
$how=$_POST['how'];
switch($how){
 case 0: $result=$cyrlat->lat2cyr($input);break;
 case 1: $result=$cyrlat->cyr2lat($input);break;
}
echo "
<html>
  <head><title>latr2cyr test result</title></head>
	<body>
	<h1>Result:</h1>
	 <form action=forms_test.php method=POST>
	  <textarea rows=10 cols=60 disabled=true>$result</textarea><br>
	<h1>Try Again?</h1>
 	  <textarea name=input rows=10 cols=60>$input</textarea><br>
	  <input name=how type=radio value=0 checked>lat2cyr test<br>
	  <input name=how type=radio value=1>cyr2lat test<br> 
	  <input type=submit name=convert value='GO!'>	  
	 </form>
	</body>
</html>";
?>