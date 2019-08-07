<script>
var uploader = mw.files.uploader({
    filetypes: "zip",
    multiple: false
});

_mw_log_reload_int = false;
$(document).ready(function () {

    mw.$("#mw_uploader").append(uploader);
    
    $(uploader).bind("FileUploaded", function (obj, data) {
    	$('#mw_uploader').fadeIn();
    	// alert(data.src);
    });

    $(uploader).bind('progress', function (up, file) {
        $('#mw_uploader').hide();
        mw.$("#upload_file_info").html("<b>Uploading file " + file.percent + "%</b><br /><br />");
    });
    
    $(uploader).bind('error', function (up, file) {
        mw.notification.error("The backup must be sql or zip.");
    });
    
});
</script>
<br />
<center>
<h3>If you have a .zip theme you can install it by uploading it here.</h3>
<br />

<span id="upload_file_info" style="font-size:14px;"></span>

 <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info">
	<i class="mw-icon-upload"></i>&nbsp;
	<span><?php _e("Upload file"); ?></span>
</span>

</center>
