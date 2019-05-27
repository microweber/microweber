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
		mw.backup_import.init_progress(1);
		
		data = {}
		data.id = src;
		
		mw.backup_import.start_import();
		
		var importLogInterval = setInterval(function() {
			mw.backup_import.get_log();
		}, 2000);
	},
	
	start_import: function () {
		$.ajax({
		  dataType: "json",
		  url: mw.settings.api_url+'Microweber/Utils/BackupV2/restore',
		  data: data,
		  success: function(jsonData) {
			if (jsonData.done) {
				clearInterval(importLogInterval);
				mw.backup_import.get_progress(100);
				alert('done');
				return;
			} else {
				mw.backup_import.get_progress(jsonData.precentage);
				mw.backup_import.start_import();
			}
		  }
		});
	},
	
	get_progress: function(precent) {
		$('.mw-ui-progress-bar').css('width', precent+'%');
		$('.mw-ui-progress-percent').html(precent);
	},
	
	init_progress: function(precent) {
		var progressbar = '<div class="mw_install_progress">'+
	        '<div class="mw-ui-progress" id="backup-import-progressbar">'+
	            '<div class="mw-ui-progress-bar" style="width: '+precent+'%;"></div>'+
	            '<div class="mw-ui-progress-info">Importing...</div>'+
	            '<span class="mw-ui-progress-percent">'+precent+'%</span>'+
	        '</div>'+
	        '<div class="backup-import-modal-log"></div>'+
	    '</div>';
		$('#mw_backup_import_modal').find('.mw_modal_container').html(progressbar);
	},
	
	get_log: function() {
		$.ajax({
		    url: userfilesUrl + 'backup-import-session.log',
		    success: function (data) {
		    	data = data.replace(/\n/g, "<br />");
		    	$('#mw_backup_import_modal').find('.backup-import-modal-log').html(data);
		    },  
		    error: function() {
		    	$('#mw_backup_import_modal').find('.backup-import-modal-log').html('Loading...');
		    }
		});
	}
}