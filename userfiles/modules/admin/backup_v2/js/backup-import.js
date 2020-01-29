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
		
		var importOptions = '<div class="mw-backup-v2-import">'+

		'<img src="'+moduleImagesUrl+'1.png" class="import-image import-image-1" />'+
		'<img src="'+moduleImagesUrl+'2.png" class="import-image import-image-2" />'+
		'<img src="'+moduleImagesUrl+'3.png" class="import-image import-image-3" />'+

		'<h2 style="font-weight: bold">How do you like to import the backup content?</h2>'+
		'<br />'+

		'<label class="mw-ui-check mw-backup-v2-import-option">' +
		'<div class="option-radio">'+
		'<input type="radio" name="import_by_type" value="1" />'+
		'<span></span>'+
		'</div>'+
		'<h3>Delete all website content & import fresh backup</h3>'+
		'<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>'+
		'</label>'+


        '<label class="mw-ui-check mw-backup-v2-import-option active">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" checked="checked" value="2" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Overwrite the website content from backup</h3>'+
        '<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>'+
        '</label>'+

        '<label class="mw-ui-check mw-backup-v2-import-option">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" value="3" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Try to overwrite content by Names & Titles</h3>'+
        '<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>'+
        '</label>'+


		'<div class="mw-backup-v2-import-buttons">'+
		'<a onClick="" class="button-cancel">Cancel</a>'+
		'<button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-rounded button-start" type="submit">Start Importing</button>'+
		'</div>'+

		'</div>'
		;
		
		mw.modal({
			height: 650,
		    content: importOptions,
		    title: importContentFromFileText,
		    id: 'mw_backup_import_modal' 
		});

        var importType = $('input[name*="import_by_type"]').val();
        changeImportImages(importType);
		
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

function changeImportImages(importType)
{
	if (importType == '1') {
		$('.import-image-1').fadeIn();
		$('.import-image-2').hide();
		$('.import-image-3').hide();
	}

	if (importType == '2') {
		$('.import-image-1').hide();
		$('.import-image-2').fadeIn();
		$('.import-image-3').hide();
	}

	if (importType == '3') {
		$('.import-image-1').hide();
		$('.import-image-2').hide();
		$('.import-image-3').fadeIn();
	}
}

$(document).ready(function () {

    $(document).on('change', 'input[name*="import_by_type"]', function() {
        var importType = $(this).val();

        $('.mw-backup-v2-import-option').removeClass('active');
        $(this).parent().parent().addClass('active');

        changeImportImages(importType);
    });

});