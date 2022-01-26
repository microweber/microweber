mw.backup = {

	choice: function(template_holder) {

        mw.log(mw.$(template_holder).html());

		this.dialog = mw.dialog({
		    title: 'Creating full backup',
		    id: 'mw_backup_export_modal',
            content: mw.$(template_holder).html(),
            width: 595,
            closeButton: false,
            closeOnEscape: false,
		});

	},

	export_selected: function(manifest) {
	    var scope = this;
		mw.backup_export.get_log_check('start');
		manifest.format = $('.js-export-format').val();
		$.get(route('admin.backup.export'), manifest , function(exportData) {
			if (exportData.data.download) {
			    scope.done = true;
				mw.backup_export.get_log_check('stop');
                scope.exportLog('<a href="'+exportData.data.download+'" class="mw-ui-btn" style="font-weight:bold;"><i class="mw-icon-download"></i> &nbsp;Download your backup</a>');
			 	mw.notification.success(exportData.success);
                mw.progress({
                    element:'.js-export-log',
                    action:''
                }).hide();
                $('.export-step-4-action').html('Export completed!');
                mw.reload_module('admin/backup/manage');
			} else {
				mw.backup_export.export_selected(manifest);
			}
			if(exportData.precentage){
			    mw.progress({
                    element:'.js-export-log',
                    action:''
                }).set(exportData.precentage);
            }
		 });
	},
    exportLogNode: null,
    exportLogContent: null,
    exportLog: function (string) {
        if(!this.exportLogNode) {
            this.exportLogNode = $('.js-export-log');
            this.exportLogContent = $('<div class="js-export-log-content"></div>');
            this.exportLogNode.append(this.exportLogContent);
        }
        this.exportLogContent.html(string);
    },
	get_log_check: function(action) {
        mw.backup_export.get_log();
	},
    done:false,
    canGet: true,
	get_log: function(c) {
	    if(!this.canGet) return;
        this.canGet = false;
        var scope = this;
		$.ajax({
		    url: userfilesUrl + 'backup-export-session.log',
		    success: function (data) {
		        if(scope.done) {
                    scope.done = false;
                }
                else {
                    data = data.replace(/\n/g, "<br />");
                    scope.exportLog(data);
                    setTimeout(function () {
                        scope.canGet = true;
                        scope.get_log();
                    }, 2222);
                }

		    },
		    error: function() {
               // scope.exportLog('Error opening log file.');
		    },
            always: function () {

            }
		});
	},

	export_fullbackup_start: function() {

        this.exportLog('Generating full backup...');
        var send_uri = 'all=true&format=' + $('.js-export-format').val() + '&include_media=true';

        var include_modules = [];
        var include_templates = [];

        $('.js-export-modules:checked').each(function(){
            include_modules.push(this.value);
        });
        $('.js-export-templates:checked').each(function(){
            include_templates.push(this.value);
        });

        if (include_modules) {
            send_uri += '&include_modules=' + include_modules.join(',');
        }

        if (include_templates) {
            send_uri += '&include_templates=' + include_templates.join(',');
        }

        mw.backup_export.export_selected(send_uri);
        mw.backup_export.stepper.last();

        $('.export-step-4-action').html('Exporting your content');
	}
};
