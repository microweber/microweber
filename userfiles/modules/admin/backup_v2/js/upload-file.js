mw.require("files.js");

_mw_log_reload_int = false;

$(document).ready(function () {
    var uploader = mw.files.uploader({
        filetypes: "zip, sql, json, csv, xls, xlsx, xml",
        multiple: false
    });

    mw.$("#mw_uploader").append(uploader);

    $(uploader).bind("FileUploaded", function (obj, data) {
        mw.backup_import.upload(data.src);

        mw.$("#mw_uploader_loading").hide();
        mw.$("#mw_uploader").show();
        mw.$("#upload_file_info").html("");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});
        mw.reload_module('admin/backup_v2');
    });

    $(uploader).bind('progress', function (up, file) {
        mw.$("#mw_uploader").hide();
        mw.$("#mw_uploader_loading").show();
        $('#mw_uploader_loading .progress-bar').html('Uploading file...<span id="upload_file_info"></span>');
        // mw.$("#upload_file_info").html(file.percent + "%");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': file.percent + "%"});
    });

    $(uploader).bind('error', function (up, file) {
        mw.notification.error("The backup must be sql or zip.");
    });
});