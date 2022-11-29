<script>
    mw.require("files.js");
</script>

<script>
var uploader = mw.files.uploader({
    filetypes: ".xlsx, .xls",
    multiple: false,
    element: mw.$("#mw_uploader")
});

_mw_log_reload_int = false;
$(document).ready(function () {


    $(uploader).on("FileUploaded", function (obj, data) {
    	$('#mw_uploader').fadeIn();
    	$('#upload_file_info').hide();
    	mw.notification.success("Moving uploaded file...");

    	postData = {}
    	postData.src = data.src;

        var replace_values = $('#replace_valuesCheck1:checked').val();

        if(replace_values){
    	postData.replace_values = 1;
        }
		$.post("<?php echo route('admin.language.import'); ?>", postData,
			function(msg) {
				if (msg.success) {
			    	mw.reload_module('.js-language-edit-browse-<?php echo xss_clean($_POST['namespaceMD5']);?>');
			    }
				mw.notification.msg(msg);
		});
    });

    $(uploader).on('progress', function (up, file) {
        $('#mw_uploader').hide();
        $('#upload_file_info').show();
        mw.$("#upload_file_info").html("<b>Uploading file " + file.percent + "%</b><br /><br />");
    });

    $(uploader).on('error', function (up, file) {
        mw.notification.error("The template must be zip.");
    });

});
</script>

<div class="my-2">
<label class="control-label"><?php _e('Upload Your Language File');?></label>
    <small class="text-muted d-block mb-3"><?php _e('If you have a .xlsx translated file you can import it by uploading it here.');?></small>

    <div class="custom-control custom-checkbox">
        <input type="checkbox" name="replace_values" value="1" class="custom-control-input" id="replace_valuesCheck1"  >
        <label class="custom-control-label" for="replace_valuesCheck1"><?php _e('Replace language values');?></label>
    </div>


<span id="upload_file_info" style="font-size:14px;"></span>
 <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info">
	<i class="mw-icon-upload"></i> &nbsp;
	<span><?php _e("Upload file"); ?></span>
</span>
</div>
