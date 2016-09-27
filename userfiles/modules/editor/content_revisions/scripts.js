mw.content_revisions_control = {
    load_content_field_to_editor: function (revision_id) {
        var data = {}
        data.id = revision_id
        var url = mw.settings.api_url + 'mw_drafts_load_content_field_to_editor';
        $.get(url, data)
            .done(function (data) {
                if (data) {
                    if (typeof window.top.mweditor !== 'undefined') {
                        var ed = window.top.mweditor;
                        $(ed).contents().find("#mw-iframe-editor-area").find('.edit').first().html(data);
                       // ed.contentWindow.mw.reload_module('.module');
                        mw.notification.warning('Revision content is loaded in the editor',5000);
                    }
                }
            });
    }

}

