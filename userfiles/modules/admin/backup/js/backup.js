mw.backup = {

    choice: function (template_holder) {

        mw.log(mw.$(template_holder).html());

        backup_modal = mw.dialog({
            title: 'Create new backup',
            id: 'mw_backup_modal',
            content: mw.$(template_holder).html(),
            width: 800,
            closeButton: false,
            closeOnEscape: false,
        });

    },

    close_modal: function() {
        backup_modal.remove();
    },

    export: function (sendParams) {

        var scope = this;

        $.get(route('admin.backup.start'), sendParams, function (export_data) {

            if (export_data.current_step == 1) {
                mw.backup.get_log_check('start');
            }

            if (export_data.data.download) {
                scope.done = true;
                mw.backup.get_log_check('stop');
                scope.exportLog('<a href="' + export_data.data.download + '" class="mw-ui-btn" style="font-weight:bold;"><i class="mw-icon-download"></i> &nbsp;Download your backup</a>');
                mw.notification.success(export_data.success);
                mw.progress({
                    element: '.js-export-log',
                    action: ''
                }).hide();

                mw.reload_module('admin/backup/manage');
            } else {
                mw.backup.export(session_id);
            }
            if (export_data.precentage) {
                mw.progress({
                    element: '.js-export-log',
                    action: ''
                }).set(export_data.precentage);
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
            url: userfilesUrl + 'cache/backup/backup-session.log',
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

    start: function () {

        var include_tables = [];
        var include_modules = [];
        var include_templates = [];
        backupType = $('input[name*="backup_by_type"]:checked');

        $('.js-include-tables:checked').each(function(){
            include_tables.push(this.value);
        });
        $('.js-include-modules:checked').each(function(){
            include_modules.push(this.value);
        });
        $('.js-include-templates:checked').each(function(){
            include_templates.push(this.value);
        });

        var instance = this;
        instance.exportLog('Generating session id...');

        $.get(route('admin.backup.generate-session-id'), {}, function (data) {
            instance.exportLog('Generating full backup...');
            mw.backup.export({
                session_id: data.session_id,
                type: backupType.val(),
                include_tables: include_tables,
                include_modules: include_modules,
                include_templates: include_templates,
            });
        });
    },

    choice_tab: function () {


    },

    next: function () {

        backupType = $('input[name*="backup_by_type"]:checked');

        $('#mw-backup-type').hide();

        if(backupType.val() == 'custom') {
            $('#mw-backup-custom').show();
        }

        mw.backup.start();

    }

};
