<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uploadify Example Script</title>
<link href="/example/css/default.css" rel="stylesheet" type="text/css" />
<link href="/example/css/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/example/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/example/scripts/swfobject.js"></script>
<script type="text/javascript" src="/example/scripts/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#uploadify").uploadify({
		'uploader'       : 'scripts/uploadify.swf',
		'script'         : 'scripts/uploadify.php',
		'cancelImg'      : 'cancel.png',
		'folder'         : 'uploads',
		'queueID'        : 'fileQueue',
		'method'		 : "POST",
		'auto'           : true,
		'multi'          : true
	});
});
</script>
</head>

<body>
<div id="fileQueue"></div>
<input type="file" name="uploadify" id="uploadify" />
<p><a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancel All Uploads</a></p>
</body>
</html>
