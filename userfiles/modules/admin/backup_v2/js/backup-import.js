// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');

mw.backup_import = {
	
	upload: function(src) {
		data = {}
		data.src=src;
		$.post(mw.settings.api_url+'Microweber/Utils/BackupV2/upload', data ,
			function(msg) {
				mw.reload_module('admin/backup_v2/manage');
				mw.notification.msg(msg, 5000);
			});
	},

	remove: function($id, $selector_to_hide) {
		mw.tools.confirm(mw.msg.del, function() {
			data = {}
			data.id = $id;
			$.post(mw.settings.api_url + 'Microweber/Utils/BackupV2/delete', data, function(resp) {
				mw.notification.msg(resp);
				if ($selector_to_hide != undefined) {
					mw.reload_module('admin/backup_v2/manage');
				}
			});
		});
	},
	
	import: function(src) {
		
		mw.modal({
		    content: '',
		    title: importContentFromFileText,
		    id: 'mw_backup_import_modal' 
		});
		
		data = {}
		data.id = src;
		
		mw.backup_import.start_import();
	},
	
	start_import: function () {
		mw.backup_import.get_log();
		$.post(mw.settings.api_url+'Microweber/Utils/BackupV2/restore', data , function(msg) {
			mw.backup_import.get_log();
			mw.backup_import.start_import();
		});
	},
	
	get_log: function() {
		$.ajax({
		    url: userfilesUrl + 'backup-import-session.log',
		    success: function (data) {
		    	data = data.replace(/\n/g, "<br />");
		    	$('#mw_backup_import_modal').find('.mw_modal_container').html(data);
		    },  
		    error: function() {
		    	$('#mw_backup_import_modal').find('.mw_modal_container').html('Loading...');
		    }
		});
	}
}