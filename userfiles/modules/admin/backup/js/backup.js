mw.backup = {

    choice: function (template_holder) {

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

    export_selected: function () {

        var scope = this;
        mw.backup.get_log_check('start');

        $.get(route('admin.backup.start'), {}, function (exportData) {
            if (exportData.data.download) {
                scope.done = true;
                mw.backup.get_log_check('stop');
                scope.exportLog('<a href="' + exportData.data.download + '" class="mw-ui-btn" style="font-weight:bold;"><i class="mw-icon-download"></i> &nbsp;Download your backup</a>');
                mw.notification.success(exportData.success);
                mw.progress({
                    element: '.js-export-log',
                    action: ''
                }).hide();
                $('.export-step-4-action').html('Export completed!');
                mw.reload_module('admin/backup/manage');
            } else {
                mw.backup.export_selected();
            }
            if (exportData.precentage) {
                mw.progress({
                    element: '.js-export-log',
                    action: ''
                }).set(exportData.precentage);
            }
        });
    },
    exportLogNode: null,
    exportLogContent: null,
    exportLog: function (string) {
        if (!this.exportLogNode) {
            this.exportLogNode = $('.js-export-log');
            this.exportLogContent = $('<div class="js-export-log-content"></div>');
            this.exportLogNode.append(this.exportLogContent);
        }
        this.exportLogContent.html(string);
    },
    get_log_check: function (action) {
        mw.backup.get_log();
    },
    done: false,
    canGet: true,
    get_log: function (c) {
        if (!this.canGet) return;
        this.canGet = false;
        var scope = this;
        $.ajax({
            url: userfilesUrl + 'backup-export-session.log',
            success: function (data) {
                if (scope.done) {
                    scope.done = false;
                } else {
                    data = data.replace(/\n/g, "<br />");
                    scope.exportLog(data);
                    setTimeout(function () {
                        scope.canGet = true;
                        scope.get_log();
                    }, 2222);
                }

            },
            error: function () {
                // scope.exportLog('Error opening log file.');
            },
            always: function () {

            }
        });
    },

    export_fullbackup_start: function () {

        this.exportLog('Generating full backup...');

        mw.backup.export_selected();

        $('.export-step-4-action').html('Backup your content');
    }
};
