mw.content_revisions_control = {
    scroll_content_field_to_editor : function (field, rel_type) {

        var targetMw = mw.top();

        var targetWindow = mw.top().app.canvas.getWindow();
        if(targetWindow) {
            var targetMw = targetWindow.mw;
        }



        var el = targetMw.$('body').find('.edit[field="' + field + '"][rel="' + rel_type + '"]').first();
        if (el && el[0]) {

            if (!targetMw.tools.wholeinview(el[0])) {
                targetMw.tools.scrollTo(el[0]);
                targetMw.tools.highlight(el[0]);
            }
        }
    },
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

                var targetMw = mw.top();

                var targetWindow = mw.top().app.canvas.getWindow();
                if(targetWindow) {
                    var targetMw = targetWindow.mw;
                }




                if (data) {

                     if(targetMw){
                         var el = targetMw.$('body').find('.edit[field="'+msg.field+'"][rel="'+msg.rel_type+'"]') ;


                         if(el && el[0]){

                             el.html(data);
                             el.addClass('changed');


                             if(!mw.tools.inview(el[0])){
                                 targetMw.tools.scrollTo(el[0]);
                                 targetMw.tools.highlight(el[0]);
                             }
                             mw.notification.warning('Revision content is loaded in the editor',5000);

                         }


                    } else  if (typeof targetMw.win.mweditor !== 'undefined') {
                        //when in admin panel
                        var ed = targetMw.win.mweditor;
                        $(ed).contents().find("#mw-iframe-editor-area").find('.edit').first().html(data);
                       // ed.contentWindow.mw.reload_module('.module');
                        mw.notification.warning('Revision content is loaded in the editor',5000);
                    }
                }
            });
    }

}

