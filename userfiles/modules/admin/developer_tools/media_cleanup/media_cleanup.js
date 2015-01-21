// JavaScript Document


mw.media_cleanup = {

    run: function (selector) {
         mw.reload_module_interval("#mw_media_cleanup_log", 3000);
        mw.notification.success("Cleanup is started...", 7000);
        $.post(mw.settings.api_url + 'admin/developer_tools/media_cleanup/Worker/create_batch', false,
            function (msg) {
                mw.notification.msg(msg);
            });
    }
}
