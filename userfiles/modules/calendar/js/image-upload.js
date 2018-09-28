$(document).ready(function() {
	
	// IMAGE UPLOADING
	var uploader = mw.uploader({
		filetypes: "images",
		multiple: false,
		element: "#mw_uploader"
	});

	$(uploader).bind("FileUploaded", function(event, data) {
		mw.$("#mw_uploader_loading").hide();
		mw.$("#mw_uploader").show();
		mw.$("#upload_info").html("");
		$('input[name="image_url"]').val(data.src);
	});

	$(uploader).bind('progress', function(up, file) {
		mw.$("#mw_uploader").hide();
		mw.$("#mw_uploader_loading").show();
		mw.$("#upload_info").html(file.percent + "%");
	});

	$(uploader).bind('error', function(up, file) {
		mw.notification.error("The file is not uploaded.");
	});
	
});