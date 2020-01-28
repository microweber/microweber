// JavaScript Document
mw.require('forms.js');
// mw.require('tools.js');

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
		
		var checked = '';
		
		if (src.lastIndexOf('backup') >= 0) {
			checked = 'checked="checked"';
		}
		
		if (src.lastIndexOf('mw_default_content') >= 0) {
			checked = 'checked="checked"';
		}

        if (src.lastIndexOf('mw_content') >= 0) {
            checked = 'checked="checked"';
        }
		
		var importOptions = '<div>'+
		'<h3>Import File Options</h3>'+
		'<br />'+
		'<label class="mw-ui-check" style="height: 26px;">'+ 
		'<input type="checkbox" class="js-backup-import-overwrite-by-id" value="1" '+checked+' />'+
		'<span style="margin-top:18px;" ></span><span>Overwrite existing content by ID.</span>'+
		'</label>'+
		'<br /><span style="color:red;margin-left:26px;">Warning! If this option are marked, it will be replace all existing posts.</span>'+
		'<hr />'+
        '<div style="margin-bottom:20px;" class="js-backup-import-installation-language-wrapper"></div>'+
		'<div><a class="mw-ui-btn mw-ui-btn-warn" onclick="mw.backup_import.start_import_button()">Start importing content</a></div>'+
		'</div>'+
		'<br /><br /><div class="backup-import-modal-log-progress"></div>'+
		'';
		
		mw.modal({
			height: 570,
		    content: importOptions,
		    title: importContentFromFileText,
		    id: 'mw_backup_import_modal' 
		});
		
		data = {};
		data.id = src;
	},
	
	start_import_button: function (src) {
		$('#mw_backup_import_modal').find('.backup-import-modal-log-progress').html('Loading file...');
		mw.backup_import.init_progress(1);
		mw.backup_import.start_import();
		mw.backup_import.get_log_check('start');
	},
	
	start_import: function () {

        $('.backup-import-modal-log-progress').show();

		data.installation_language = $('.js-backup-import-installation-language').val();
		data.overwrite_by_id = $('.js-backup-import-overwrite-by-id').is(":checked");
		
		$.ajax({
		  dataType: "json",
		  url: mw.settings.api_url+'Microweber/Utils/BackupV2/import',
		  data: data,
		  success: function(json_data) {

		  	if (json_data.must_choice_language) {
		  		mw.backup_import.choice_language(json_data.detected_languages);
                mw.backup_import.get_log_check('stop');
		  		return;
			}

			if (json_data.error) {
				$('#backup-import-progressbar').remove();
				mw.backup_import.get_log_check('stop');
				$('#mw_backup_import_modal').find('.backup-import-modal-log').before('<h3>Error!</h3><br />' + json_data.error);
				return; 
			}
			if (json_data.done) {
				mw.backup_import.get_progress(100);
				mw.backup_import.get_log_check('stop');
				$('#mw_backup_import_modal').find('.backup-import-modal-log').before('<h3>All data is imported successfully!</h3>');
				return; 
			} else {
				mw.backup_import.get_progress(json_data.precentage);
				mw.backup_import.start_import();
			}
		  }
		});
	},

    choice_language: function(languages) {

		var select = '<p style="background: #009cff;color: #fff;padding: 7px;font-size: 15px;">Select installation language:</p><br />';

		select += '<select class="mw-ui-field js-backup-import-installation-language" name="installation_language">';
    	select += '<option value="en">EN</option>';

        for (i = 0; i < languages.length; i++) {
            select += '<option value="'+languages[i]+'">'+languages[i].toUpperCase()+'</option>';
        }

        select += '</select>';

		$('.js-backup-import-installation-language-wrapper').html(select);
		$('.backup-import-modal-log-progress').hide();
	},

	get_log_check: function(action = 'start') {
		
		var importLogInterval = setInterval(function() {
			mw.backup_import.get_log();
		}, 2000);
		
		if (action == 'stop') {
			for(i=0; i<10000; i++) {
		        window.clearInterval(i);
		    }
		}
		
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
		$('#mw_backup_import_modal').find('.backup-import-modal-log-progress').html(progressbar);
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