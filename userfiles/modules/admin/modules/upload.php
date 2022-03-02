
<script>
    mw.require("files.js");
</script>
<script>
    var uploader = mw.files.uploader({
        filetypes: "zip",
        multiple: false,
        element: mw.$("#mw_uploader")
    });

    _mw_log_reload_int = false;
    $(document).ready(function () {

        $(uploader).bind("FileUploaded", function (obj, data) {
            $('#mw_uploader').fadeIn();
            $('.overwrite-existing-checkobx').fadeIn();
            $('#upload_file_info').hide();
            mw.notification.success("Moving uploaded file...");

            postData = {}
            postData.src = data.src;
            postData.overwrite = 0

            if ($('#overwrite_existing_template').is(':checked')) {
                postData.overwrite = 1;
            }

            $.post('<?php echo route('api.module.upload');?>', postData, function(msg) {
                mw.notification.msg(msg, 5000);
            });
        });

        $(uploader).bind('progress', function (up, file) {
            $('#mw_uploader').hide();
            $('.overwrite-existing-checkobx').hide();
            $('#upload_file_info').show();
            mw.$("#upload_file_info").html("<b>Uploading file " + file.percent + "%</b><br /><br />");
        });

        $(uploader).bind('error', function (up, file) {
            mw.notification.error("The module must be zip.");
        });

    });
</script>
<br />
<center>
    <h4><?php _e("If you have a .zip module you can install it by uploading it here"); ?>.</h4>
    <br />

    <span id="upload_file_info" style="font-size:14px;"></span>

    <label class="mw-ui-check overwrite-existing-checkbox">
        <input type="checkbox" value="1" name="overwrite_existing_module" id="overwrite_existing_module">
        <span></span><span><?php _e("Overwrite existing module"); ?></span>
    </label>

    <br />
    <br />

    <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info">
	<i class="mw-icon-upload"></i>&nbsp;
	<span><?php _e("Upload file"); ?></span>
</span>

</center>
