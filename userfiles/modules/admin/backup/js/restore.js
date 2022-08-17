// JavaScript Document
mw.require('forms.js');

mw.restore = {

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

		var importOptions = '<div class="mw-backup-restore">'+

		'<div class="mw-backup-restore-options">'+
		'<img src="'+moduleImagesUrl+'1.png" class="import-image import-image-1" />'+
		'<img src="'+moduleImagesUrl+'2.png" class="import-image import-image-2" />'+
		'<img src="'+moduleImagesUrl+'3.png" class="import-image import-image-3" />'+

		'<h2 style="font-weight: bold">How do you like to restore your content?</h2>'+
		'<br />'+

		'<label class="mw-ui-check mw-backup-restore-option">' +
		'<div class="option-radio">'+
		'<input type="radio" name="import_by_type" value="1" />'+
		'<span></span>'+
		'</div>'+
		'<h3>Delete all website content & restore</h3>'+
		'<p></p>'+
		'</label>'+


        '<label class="mw-ui-check mw-backup-restore-option active">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" checked="checked" value="2" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Overwrite the website content from backup</h3>'+
        '<p></p>'+
        '</label>'+


        '<label class="mw-ui-check mw-backup-restore-option">' +
        '<div class="option-radio">'+
        '<input type="radio" name="import_by_type" value="3" />'+
        '<span></span>'+
        '</div>'+
        '<h3>Try to overwrite content by Names & Titles</h3>'+
        '<p></p>'+
        '</label>'+

		'</div>' +

		'<div style="margin-bottom:20px;" class="js-backup-restore-installation-language-wrapper"></div>'+
		'<div class="backup-restore-modal-log-progress"></div>'+

		'<div class="mw-backup-restore-buttons">'+
		'<a class="btn btn-link button-cancel" onClick="mw.restore.close_restore_modal();">Close</a>'+
		'<button class="btn btn-primary btn-rounded button-start" onclick="mw.restore.start_restore_button()" type="submit">Start Restore</button>'+
		'</div>'+

		'</div>'
		;

		restoreModal = mw.dialog({
			width: 680,
		    content: importOptions,
		    title: importContentFromFileText,
		    id: 'mw_backup_restore_modal'
		});

        changeImportImages(2);

		data = {};
		data.id = src;
	},

	close_restore_modal: function() {
        restoreModal.remove();
	},

	start_restore_button: function (src) {

        $('#mw_backup_restore_modal').find('.backup-restore-modal-log-progress').html('Generating session id...');

        $.get(route('admin.backup.generate-session-id'), {}, function (data) {

            $('#mw_backup_restore_modal').find('.backup-restore-modal-log-progress').html('Loading file...');
            $('#mw_backup_restore_modal').find('.mw-backup-restore-options').slideUp();

            mw.restore.init_progress(1);
            mw.restore.start_import(data.session_id);
            mw.restore.get_log_check('start');

        });

	},

	start_import: function (session_id) {

		mw.notification.success('Importing...', 10000, 'import');

		$('.button-start').addClass('disabled');
		$('.backup-restore-modal-log-progress').show();

        var import_by_type = $('input[name="import_by_type"]:checked').val();

        data.session_id = session_id;

		data.installation_language = $('.js-backup-restore-installation-language').val();

		if (import_by_type == '1') {
            data.import_by_type = 'delete_all';
        }
        if (import_by_type == '2') {
            data.import_by_type = 'overwrite_by_id';
        }
        if (import_by_type == '3') {
            data.import_by_type = 'overwrite_by_titles';
        }

		$.ajax({
		  dataType: "json",
		  url: route('admin.backup.restore'),
		  data: data,
		  success: function(json_data) {

			if (json_data.error) {
				$('.button-start').removeClass('disabled');
				$('#backup-restore-progressbar').remove();
				mw.restore.get_log_check('stop');
				$('#mw_backup_restore_modal').find('.backup-restore-modal-log').before('<h3>Error!</h3><br />' + json_data.error);
				return;
			}

			if (json_data.done) {

                mw.restore.reload_mw_db();
                mw.clear_cache();

				mw.restore.get_progress(100);
				mw.restore.get_log_check('stop');
				//mw.spinner({element: ".button-start", size: 30, color: 'white'}).hide()
				$('.button-start').removeClass('disabled');

				$('#mw_backup_restore_modal').find('.backup-restore-modal-log').before('<h3>All data is restored successfully!</h3>');
                $('#mw_backup_restore_modal').find('.button-start').html('Done');
                $('#mw_backup_restore_modal').find('.button-start').attr('onClick', 'mw.restore.close_restore_modal();').html('Done!');
				return;
			} else {
				mw.restore.get_progress(json_data.percentage);
				if($('.backup-restore-modal-log').length > 0){
					$('.backup-restore-modal-log')[0].scrollTop =$('.backup-restore-modal-log')[0].scrollHeight;
				}
				setTimeout(function(){
                    mw.restore.start_import(json_data.session_id);
                }, 300);

			}
		  }
		});
	},

    reload_mw_db: function () {
        $.post(mw.settings.api_url + 'mw_post_update');
        mw.notification.success("The DB was reloaded");
    },

	get_log_check: function(action = 'start') {

		var importLogInterval = setInterval(function() {
			mw.restore.get_log();
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
	        '<div class="mw-ui-progress" id="backup-restore-progressbar">'+
	            '<div class="mw-ui-progress-bar" style="width: '+precent+'%;"></div>'+
	            '<div class="mw-ui-progress-info">Importing...</div>'+
	            '<span class="mw-ui-progress-percent">'+precent+'%</span>'+
	        '</div>'+
	        '<div class="backup-restore-modal-log"></div>'+
	    '</div>';
		$('#mw_backup_restore_modal').find('.backup-restore-modal-log-progress').html(progressbar);
	},

	get_log: function() {
		$.ajax({
		    url: userfilesUrl + 'cache/backup/backup-session.log',
		    success: function (data) {
		    	data = data.replace(/\n/g, "<br />");
		    	$('#mw_backup_restore_modal').find('.backup-restore-modal-log').html(data);
		    },
		    error: function() {
		    	$('#mw_backup_restore_modal').find('.backup-restore-modal-log').html('Loading...');
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

        $('.mw-backup-restore-option').removeClass('active');
        $(this).parent().parent().addClass('active');

        changeImportImages(importType);
    });

});
