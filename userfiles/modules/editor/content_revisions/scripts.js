mw.content_revisions_control = {
    load_content_field_to_editor: function (revision_id) {
        var data = {}
        data.id = revision_id
        data.return_full = true
        var url = mw.settings.api_url + 'mw_drafts_load_content_field_to_editor';
        $.get(url, data)
            .done(function (msg) {
                if (typeof (msg.value) === 'undefined') {
                    return;
                }
               var data = msg.value

                if (data) {

                     if(mw.top().liveedit){
                         var el = mw.top().$('body').find('.edit[field="'+msg.field+'"][rel="'+msg.rel_type+'"]') ;


                         if(el && el[0]){

                             el.html(data);
                             if(!mw.tools.inview(el[0])){
                                 mw.top().tools.scrollTo(el[0]);
                                 mw.top().tools.highlight(el[0]);
                             }
                             mw.notification.warning('Revision content is loaded in the editor',5000);

                         }


                    } else  if (typeof mw.top().win.mweditor !== 'undefined') {
                        //when in admin panel
                        var ed = mw.top().win.mweditor;
                        $(ed).contents().find("#mw-iframe-editor-area").find('.edit').first().html(data);
                       // ed.contentWindow.mw.reload_module('.module');
                        mw.notification.warning('Revision content is loaded in the editor',5000);
                    }


                }
            });
    }

}

