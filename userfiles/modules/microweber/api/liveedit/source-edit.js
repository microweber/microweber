
mw.lib.require('codemirror');
(function (){
    var _editSource = {};

    mw.editSource = function (node) {
        var area = document.createElement('textarea');
        var ok = document.createElement('mwbtn');
        var cancel = document.createElement('mwbtn');
        var nav = document.createElement('div');
        var validator = document.createElement('div');

        mw.$(ok).addClass('mw-ui-btn mw-ui-btn-medium mw-ui-btn-info').html(mw.lang('OK'));
        mw.$(cancel).addClass('mw-ui-btn mw-ui-btn-medium').html(mw.lang('Cancel'));

        mw.$(area).on('input', function () {
            validator.innerHTML = area.value;
            ok.disabled = validator.innerHTML !==  area.value;
            ok.classList[ok.disabled ? 'add' : 'remove']('disabled');
            var hasErr = mw.$('.mw-inline-source-editor-error', nav);
            if(ok.disabled) {
                if(!hasErr.length) {
                    mw.$(nav).prepend('<span class="mw-inline-source-editor-error">' + mw.lang('Invalid HTML') + '</span>');
                }
            }
            else {
                hasErr.remove();
            }
        });

        mw.$(ok).on('click', function () {
            if(!ok.disabled){
                mw.$(node).html( area.value);
                dlg.remove()
                mw.wysiwyg.change( node);
            }
        });
        mw.$(cancel).on('click', function () {
            if(!cancel.disabled){
                 dlg.remove()

            }
        });

        nav.appendChild(cancel)
        nav.appendChild(ok)

        var dlg = mw.dialog({
            overlay: true,
            width: '90%',
            height: 'calc(100vh - 200px)',
            footer: nav,
            content: area,
            title: mw.lang('Edit element HTML content')
        });
        area.value = node.innerHTML;

        var _codeMirror = CodeMirror.fromTextArea(area, {
            lineNumbers: true,
            lineWrapping: true,
            matchTags: {bothTags: true},
            indentWithTabs: true,
            matchBrackets: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete",
                "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                "Ctrl-J": "toMatchingTag"
            },
            mode: {
                name: "htmlmixed",
                scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                    mode: null},
                    {matches: /(text|application)\/(x-)?vb(a|script)/i,
                        mode: "vbscript"}]
            },
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
        });
        _codeMirror.setOption("theme", 'material');
        _codeMirror.setSize("100%", "100%");
        _codeMirror.on("change", function (cm, change) {

             area.value = cm.getValue();
            mw.$(area).trigger('input')
        });
        dlg.container.style.height = 'calc(100% - 120px)';
        dlg.container.querySelector('.CodeMirror').style.height = 'calc(100%)';
        if(mw._initHandles){
            mw._initHandles.hideAll();
        }
        _editSource.target = node;
    };

})()
