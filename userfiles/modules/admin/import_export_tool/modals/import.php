<script>
mw.require("files.js");
mw.require("uploader.js");
$(document).ready(function () {
    var uploader = mw.upload({
        filetypes: "zip, sql, json, csv, xls, xlsx, xml",
        multiple: false,
        element:$("#mw_uploader")
    });

    $(uploader).bind("FileUploaded", function (obj, data) {

        //data.src

        mw.$("#mw_uploader_loading").hide();
        mw.$("#upload_file_info").html("");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});

        mw.notification.success('File uploaded');
    });

    $(uploader).bind('progress', function (up, file) {
        mw.$("#mw_uploader_loading").show();
        $('#mw_uploader_loading .progress-bar').html('Uploading file...' + file.percent + "%");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': file.percent + "%"});
    });

    $(uploader).bind('error', function (up, file) {
        mw.$("#mw_uploader_loading").hide();
        mw.$("#upload_file_info").html("");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});
        mw.notification.error("The backup must be sql or zip.");
    });
});

function importSelectType(type)
{
    if (type == 'wordpress') {

    }

    $('.import-modal-select-file-upload').show();
    $('.import-modal-vendors').hide();
}
</script>

<div class="import-modal-select-file-upload" style="display:none">
    <div class="row">
    <div class="col-12 mb-4">
        <h5 class="font-weight-bold"><?php _e('Upload file with content'); ?></h5>
        <small class="text-muted d-block mb-3"><?php _e("Upload your content file"); ?></small>
        <span id="mw_uploader" class="btn btn-primary btn-rounded"><i class="mdi mdi-cloud-upload-outline"></i>&nbsp; <?php _e("Select file"); ?></span>

        <div id="mw_uploader_loading" class="progress mb-3" style="display:none;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            </div>
        </div>
    </div>
    </div>
</div>

<div class="import-modal-vendors">
    <h6>
        <?php _e("Select type of import"); ?>
    </h6>
    <div class="import-modal-vendor-btn" onclick="importSelectType('wordpress')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/wordpress.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Wordpress"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('woocommerce')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/woocommerce.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("WooCommerce"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('shopify')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/shopify.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Shopify"); ?></div>
    </div>
    <div class="import-modal-vendor-btn" onclick="importSelectType('Feed')">
        <div class="import-modal-vendor-btn-image">
            <img src="<?php echo modules_url(); ?>/admin/import_export_tool/images/feed.svg"/>
        </div>
        <div class="import-modal-vendor-btn-title"><?php _e("Feed"); ?></div>
    </div>
</div>
