<!DOCTYPE html>
<html>
<head>
<title>SWFUpload Demos - Graceful Degradation Demo</title>
<link href="../css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../swfupload/swfupload.js"></script>
<script type="text/javascript" src="../swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript">
var swfu;

SWFUpload.onload = function () {
	var settings = {
		flash_url : "../swfupload/swfupload.swf",
		flash9_url : "../swfupload/swfupload_fp9.swf",
		upload_url: "upload.php",
		post_params: {
			"PHPSESSID" : "NONE",
			"HELLO-WORLD" : "Here I Am",
			".what" : "OKAY"
		},
		file_size_limit : "100 MB",
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button Settings
		button_image_url : "XPButtonUploadText_61x22.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 61,
		button_height: 22,

		// The event handler functions are defined in handlers.js
		swfupload_preload_handler : swfUploadPreLoad,
		swfupload_load_failed_handler : swfUploadLoadFailed,
		swfupload_loaded_handler : swfUploadLoaded,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete	// Queue plugin event
		
	};

	swfu = new SWFUpload(settings);
}

</script>
</head>
<body>
<div id="header">
	<h1 id="logo"><a href="../">SWFUpload</a></h1>
	<div id="version">v2.5.0</div>
</div>

<div id="content">

	<h2>SWFObject Demo</h2>
	<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
		<p>This page demonstrates SWFUpload's use of the swfobject library to gracefully degrade the page.</p>
		<p>
			SWFUpload provides a variety of events to notify your application whether SWFUpload will be able to load properly.
			Rather than SWFUpload simply degrading automatically it leaves complete control in the hands of the developer to degrade
			in a way appropriate to the application.
		</p>
		
		<p>Do each of the following (one at a time) to see the plugin work: </p>
		<ul>
			<li>Uninstall your Flash Player or install a version less than 9.0.28</li>
			<li>Cause the SWF file to fail to load by deleting or renaming swfupload.swf (simulating a very slow or failed download)</li>
			<li>Disable JavaScript</li>
		</ul>
		<p>
			Each of these tests demontrate how these issues can be handled by SWFUpload and the swfobject libraries.
		</p>
		
		<div id="divSWFUploadUI" style="margin-top: 20px;">
			<div class="fieldset  flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
			<p id="divStatus">0 Files Uploaded</p>
			<p>
				<span id="spanButtonPlaceholder"></span>
				<input id="btnCancel" type="button" value="Cancel All Uploads" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
				<br />
			</p>
		</div>
		<noscript>
			<div style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">
				We're sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.
			</div>
		</noscript>
		<div id="divLoadingContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			SWFUpload is loading. Please wait a moment...
		</div>
		<div id="divLongLoading" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.
		</div>
		<div id="divAlternateContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			We're sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player.
			Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.
		</div>
	</form>
</div>
</body>
</html>
