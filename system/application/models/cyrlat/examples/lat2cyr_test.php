<?php
if (!isset($_POST['convert'])){
	echo "
<html>
  <head><title>lat2cyr test form</title></head>
	<body>
	 <form action=lat2cyr_test.php method=POST>
	  <textarea name=input>Pro Scheloch'</textarea><br>
	  <input type=submit name=convert value='GO!'>
	 </form>
	</body>
</html>";
exit;
}
require_once("../cyrlat.class.php");
$cyrlat=new CyrLat;
$input=$_POST['input'];
$result=$cyrlat->lat2cyr($input);
echo "
<html>
  <head><title>latr2cyr test result</title></head>
	<body>
	<h1>Result:</h1>
	 <form action=lat2cyr_test.php method=POST>
	  <textarea>$result</textarea><br>
	<h1>Try Again?</h1>
 	  <textarea name=input>$input</textarea><br>
	  <input type=submit name=convert value='GO!'>	  
	 </form>
	</body>
</html>";
?>