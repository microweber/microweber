// JavaScript Document


mw.database_cleanup = {

    run: function (selector) {
         mw.reload_module_interval("#mw_database_cleanup_log", 3000);
        mw.notification.success("Cleanup is started...", 7000);
       /* $.post(mw.settings.api_url + 'admin/developer_tools/database_cleanup/Worker/run', false,
            function (msg) {
                mw.notification.msg(msg);
            });*/
    }
}
