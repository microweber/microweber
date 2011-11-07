<?php
/**
Tiny Upload

upload.php

This file does the uploading
*/

//###### Config ######
$absPthToSlf = 'http://www.example.com/images/tinyupload.php'; //The Absolute path (from the clients POV) to this file.
$absPthToDst = 'http://www.example.com/images/userimages/'; //The Absolute path (from the clients POV) to the destination folder.
$absPthToDstSvr = '/home/5586/example/www.example.com/public_html/images/userimages/'; //The Absolute path (from the servers POV) to the destination folder. You will need to set permissions for this dir 0777 worked ok for me.

function hasAccess(){
	/**
	If you need to do a securty check on your user here is where you should put the code.
	*/
	return true;
}

//###### You should not need to edit past this point ######
if($_GET["poll"]){
	$dh  = opendir($absPthToDstSvr);
	while (false !== ($filename = readdir($dh))) {
	  $files[] = $filename;
	}
	sort($files);
	
	//Filter out html files and directories.
	function filterHTML($var){
		if(is_dir($absPthToDstSvr . $var) or substr_count($var, '.html') > 0){
			return false;
		}
		else{
			return true;
		}
	}
	$files = array_filter($files, 'filterHTML');
	$str = '[';
	foreach ($files as $file){
		$str .= '{';
		$str .= '"url":"'. $absPthToDst . $file .'",';
		$str .= '"file":"'. $file .'"';
		$str .= '}, ';
	}
	$str .= ']';
	echo $str;
}
elseif (hasAccess()){

	if($_FILES['tuUploadFile']['tmp_name']){
		$target_path = $absPthToDstSvr. basename( $_FILES['tuUploadFile']['name']); 
		move_uploaded_file($_FILES['tuUploadFile']['tmp_name'], $target_path);
	}
?>
<html>
<head>
<style type="text/css">
	body{
		font-size:10px;
		margin:0px;
		padding:0px;
		height:20px;
		overflow:hidden;
	}
</style>
<script type="text/javascript">
	window.onload = function(){
		parent.tuIframeLoaded();
	}
	function tuSmt(){
		filePath = '<?php echo $absPthToDst; ?>' + document.getElementById('tuUploadFile').value;
		if(parent.tuFileUploadStarted(filePath, document.getElementById('tuUploadFile').value)){
			window.document.body.style.cssText = 'border:none;padding-top:100px';
			document.getElementById('tuUploadFrm').submit();
		}
	}
</script>
</head>
<body style="border:none;">
	<form enctype="multipart/form-data" method="post" action="<?php echo $absPthToSlf; ?>" id="tuUploadFrm">
		<div style="height:22px;vertical-align:top;">
			<input type="file" size="24" style="height:22px;" id="tuUploadFile" name="tuUploadFile" />
			<input type="button" value="Go" onclick="javascript:tuSmt();" style="margin:0px 0px 20px 2px;border:1px solid #808080;background:#fff;height:20px;"/>
		</div>
	</form>
</body>
</html>
<?php
}
?>