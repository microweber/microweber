mw.liveedit = mw.liveedit || {};
mw.liveedit.widgets = {
    htmlEditorDialog: function () {
        /*var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';
         mw.dialogIframe({
            url: src,
            title: 'Code editor',
            height: 'auto',
            width: '95%'
        });*/

        var root = document.createElement('div');
        var el = document.createElement('div');
        el.className = 'module';
        el.style.minHeight = '300px';
        el.dataset.type = 'editor/code_editor'
        el.id = 'mw_global_html_editor'
        el.setAttribute('live_edit', 'true');

        root.appendChild(el)


        var dialog = mw.dialog({
            content: root,
            title: 'Code editor',
            height: 'auto',
            width: '95%',
        });
        var spinner = mw.spinner({
            element: root,
            size: "36px",
            decorate: true
        })
        dialog.center()
        mw.reload_module(el, function (){
            dialog.center()
            spinner.remove()
        })
    },
    cssEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
        return mw.dialogIframe({
            url: src,
            // width: 500,
            height:'auto',
            autoHeight: true,
            name: 'mw-css-editor-front',
            title: 'CSS Editor',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    }
};
