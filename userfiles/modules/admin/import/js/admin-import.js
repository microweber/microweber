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
	},
	

	remove: function($id, $selector_to_hide) {
		mw.tools.confirm(mw.msg.del, function() {
			data = {}
			data.id = $id;
			$.post(mw.settings.api_url + 'Microweber/Utils/Import/delete', data, function(resp) {
				mw.notification.msg(resp);
				if ($selector_to_hide != undefined) {
					$($selector_to_hide).fadeOut().remove();
				}
			});
		});
	},
	
	restore: function(src) {
		data = {}
		data.id = src;
		$.post(mw.settings.api_url+'Microweber/Utils/Import/restore', data , function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 15000);
				//mw.reload_module('admin/import/process');
		});
	}
}