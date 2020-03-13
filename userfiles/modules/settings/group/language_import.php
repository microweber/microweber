<script>
    mw.require("files.js");
</script>

<script>
var uploader = mw.files.uploader({
    filetypes: "xlsx",
    multiple: false
});

_mw_log_reload_int = false;
$(document).ready(function () {

    mw.$("#mw_uploader").append(uploader);

    $(uploader).bind("FileUploaded", function (obj, data) {
    	$('#mw_uploader').fadeIn();
    	$('#upload_file_info').hide();
    	mw.notification.success("Moving uploaded file...");

    	postData = {}
    	postData.src = data.src;
    	postData.namespace = "<?php echo $params['namespace']; ?>";
    	postData.language = "<?php echo $params['language']; ?>";

		$.post(mw.settings.api_url+'Microweber/Utils/Language/upload', postData,
			function(msg) {
				if (msg.success) {
			    	mw.reload_module('settings/group/language_edit');
			    }
				mw.notification.msg(msg);
		});
    });

    $(uploader).bind('progress', function (up, file) {
        $('#mw_uploader').hide();
        $('#upload_file_info').show();
        mw.$("#upload_file_info").html("<b>Uploading file " + file.percent + "%</b><br /><br />");
    });

    $(uploader).bind('error', function (up, file) {
        mw.notification.error("The template must be zip.");
    });

});
</script>
<br />
<center>
<h3>If you have a .xlsx translated file you can import it by uploading it here.</h3>
<br />

<span id="upload_file_info" style="font-size:14px;"></span>

 <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info">
	<i class="mw-icon-upload"></i> &nbsp;
	<span><?php _e("Upload file"); ?></span>
</span>

</center>
