// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');

mw.admin_import = {
		
	move_uploaded_file_to_import: function(src){
		data = {}
		data.src=src;
		$.post(mw.settings.api_url+'Microweber/Utils/Import/move_uploaded_file_to_import', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 5000);
			});
	}
}