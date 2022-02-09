mw.require("files.js");
mw.require("uploader.js");

_mw_log_reload_int = false;

$(document).ready(function () {

    var uploader = mw.upload({
        filetypes: "zip",
        multiple: false,
        element:$("#mw_uploader")
    });

    $(uploader).bind("FileUploaded", function (obj, data) {

        mw.restore.upload(data.src);

        mw.$("#mw_uploader_loading").hide();
        mw.$("#upload_file_info").html("");
        mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});

        mw.reload_module('admin/backup/manage');
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
