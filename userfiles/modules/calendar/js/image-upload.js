mw.top().require('filepicker.js');
$(document).ready(function() {
	// IMAGE UPLOADING
	$('#mw_uploader,#up_img').on('click', function(){
	    var dialog = mw.top().dialog();
	    var mwtop = mw.top();
        new mwtop.filePicker({
            element: dialog.dialogContainer,
            nav: 'tabs',
            footer: true,
            boxed: false,
            label: null,
            onResult: function (data) {
                var url = data.src ? data.src : data;
                var field = $('input[name="image_url"]');
                field.val(url);
                $('#up_img').attr('src', url);
                dialog.remove();
            }
        });

    });
});
