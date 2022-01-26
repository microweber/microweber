// JavaScript Document
mw.require('forms.js');


mw.backup_import = {

	upload: function(src) {
		data = {}
		data.src=src;
		$.post(route('admin.backup.upload'), data ,
			function(msg) {
				mw.reload_module('admin/backup/manage');
				mw.notification.msg(msg, 5000);
			});
	},

	remove: function($id, $selector_to_hide) {
		mw.tools.confirm(mw.msg.del, function() {
			data = {}
			data.id = $id;
			$.post(route('admin.backup.delete'), data, function(resp) {
				mw.notification.msg(resp);
				if ($selector_to_hide != undefined) {
					mw.reload_module('admin/backup/manage');
				}
			});
		});
	},

	import: function(src) {

	/*	var checked = '';

		if (src.lastIndexOf('backup') >= 0) {
			checked = 'checked="checked"';
		}

		if (src.lastIndexOf('mw_default_content') >= 0) {
			checked = 'checked="checked"';
		}

        if (src.lastIndexOf('mw_content') >= 0) {
            checked = 'checked="checked"';
        }*/

		var importOptions = '<div class="mw-backup-v2-import">'+

		'<div class="mw-backup-v2-import-options">'+
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
		'<p></p>'+
		'</label>'+


        '<label class="mw-ui-check mw-backup-v2-import-option active">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" checked="checked" value="2" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Overwrite the website content from backup</h3>'+
        '<p></p>'+
        '</label>'+


        '<label class="mw-ui-check mw-backup-v2-import-option">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" value="3" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Try to overwrite content by Names & Titles</h3>'+
        '<p></p>'+
        '</label>'+

		'</div>' +

		'<div style="margin-bottom:20px;" class="js-backup-import-installation-language-wrapper"></div>'+
		'<div class="backup-import-modal-log-progress"></div>'+

		'<div class="mw-backup-v2-import-buttons">'+
		'<a class="btn btn-link button-cancel" onClick="mw.backup_import.close_import_modal();">Close</a>'+
		'<button class="btn btn-primary btn-rounded button-start" onclick="mw.backup_import.start_import_button()" type="submit">Start Importing</button>'+
		'</div>'+

		'</div>'
		;

		importModal = mw.dialog({
			width: 680,
		    content: importOptions,
		    title: importContentFromFileText,
		    id: 'mw_backup_import_modal'
		});

        changeImportImages(2);

		data = {};
		data.id = src;
	},

	close_import_modal: function() {
        importModal.remove();
	},

	start_import_button: function (src) {

		$('#mw_backup_import_modal').find('.backup-import-modal-log-progress').html('Loading file...');
		$('#mw_backup_import_modal').find('.mw-backup-v2-import-options').slideUp();

		mw.backup_import.init_progress(1);
		mw.backup_import.start_import();
		mw.backup_import.get_log_check('start');
	},

	start_import: function () {


		mw.notification.success('Importing...', 10000, 'import');

		$('.button-start').addClass('disabled');

		$('.backup-import-modal-log-progress').show();

        var import_by_type = $('input[name="import_by_type"]:checked').val();

		data.installation_language = $('.js-backup-import-installation-language').val();

		if (import_by_type == '1') {
            data.import_by_type = 'delete_all';
        }
        if (import_by_type == '2') {
            data.import_by_type = 'overwrite_by_id';
        }
        if (import_by_type == '3') {
            data.import_by_type = 'overwrite_by_titles';
        }

        if (typeof data.step === 'undefined') {
            data.step = 0;
		}

		$.ajax({
		  dataType: "json",
		  url: route('admin.backup.import'),
		  data: data,
		  success: function(json_data) {

              data.step = json_data.next_step;

		  	if (json_data.must_choice_language) {
		  		mw.backup_import.choice_language(json_data.detected_languages);
                mw.backup_import.get_log_check('stop');
				$('.button-start').removeClass('disabled');
		  		return;
			}

			if (json_data.error) {
				$('.button-start').removeClass('disabled');
				$('#backup-import-progressbar').remove();
				mw.backup_import.get_log_check('stop');
				$('#mw_backup_import_modal').find('.backup-import-modal-log').before('<h3>Error!</h3><br />' + json_data.error);
				return;
			}
			if (json_data.done) {
				mw.backup_import.get_progress(100);
				mw.backup_import.get_log_check('stop');
				//mw.spinner({element: ".button-start", size: 30, color: 'white'}).hide()
				$('.button-start').removeClass('disabled');

				$('#mw_backup_import_modal').find('.backup-import-modal-log').before('<h3>All data is imported successfully!</h3>');
                $('#mw_backup_import_modal').find('.button-start').html('Done');
                $('#mw_backup_import_modal').find('.button-start').attr('onClick', 'mw.backup_import.close_import_modal();').html('Done!');
				return;
			} else {
				mw.backup_import.get_progress(json_data.precentage);
				if($('.backup-import-modal-log').length > 0){


					$('.backup-import-modal-log')[0].scrollTop =$('.backup-import-modal-log')[0].scrollHeight;


				}
				//		$('#mw_backup_import_modal').
				setTimeout(function(){ mw.backup_import.start_import(); }, 300);


			}
		  }
		});
	},

    choice_language: function(languages) {

		var select = '<h2 style="font-weight: bold">Select installation language:</h2>';
		select += '<p>We are detecting a multiple language content.</p>';
		select += '<p>Please choose wich one you want to import.</p><br /><br />';

		select += '<select class="mw-ui-field js-backup-import-installation-language" style="width:100%;" name="installation_language">';
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
