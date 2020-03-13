mw.liveedit = mw.liveedit || {};
mw.liveedit.widgets = {
    htmlEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';
        window.open(src, "Code editor", "toolbar=no, menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=yes");
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
    },
    _iconEditor: null,
    _iconEditorTarget: null,
    iconEditor: function (target) {
        if(!target) return;
        this._iconEditorTarget = target;

        if(!this._iconEditor) {
            this._iconEditor = mw.icons.tooltip({element: mw.liveedit.widgets._iconEditorTarget, position: 'bottom-center', width:320}, true);

            this._iconEditor.$e.on('Result', function(e, res){
                res.render(res.icon, mw.liveedit.widgets._iconEditorTarget);
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
            this._iconEditor.$e.on('sizeChange', function(e, size){
                mw.liveedit.widgets._iconEditorTarget.style.fontSize = size ? size + 'px' : '';
                mw.tools.tooltip.setPosition(mw.liveedit.widgets._iconEditor.tooltip, mw.liveedit.widgets._iconEditorTarget, 'bottom-center');
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
            this._iconEditor.$e.on('colorChange', function(e, color){
                mw.liveedit.widgets._iconEditorTarget.style.color = color;
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
        }
        target.style.fontSize = getComputedStyle(target).fontSize;
        mw.$('.mw-field [type="number"]', mw.liveedit.widgets._iconEditor.content).val(parseFloat(target.style.fontSize))
        $(mw.liveedit.widgets._iconEditor.tooltip).show();
        mw.tools.tooltip.setPosition(this._iconEditor.tooltip, mw.liveedit.widgets._iconEditorTarget, 'bottom-center');
        var $el = $(this._iconEditor.tooltip);

        if(($el.offset().top + $el.height() + 10) > Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)){
            this._iconEditor.tooltip.scrollIntoView()
        }
    },
    _linkEditor: function (options) {
        var scope = this;
        options = options || {};
        var allSources = [
            'page',
            'content',
            'custom',
            'file',
            'email',
            'layout',
            'title'
        ];

        var defaults = {
            sources: 'all',
            target: true,
            content: true,
            title: true,
            dialogTitle: mw.lang('Edit link'),
            mode: 'dialog'
        };

        this.settings = $.extend({}, defaults, options);
        if(this.settings.sources === 'all') {
            this.settings.sources = allSources;
        }
        this._result = null;
        this.result = function (result, trigger) {
            if(!result){
                return this._result;
            }
            this._result = result;
            if(trigger) {
                $(this).trigger('Result');
            }
        };
        this.create = function () {
            if(this.settings.mode === 'dialog') {
                var footer = $('<div></div>');
                var ok = $('<span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">' + mw.lang('OK') + '</span>');
                var cancel = $('<span class="mw-ui-btn mw-ui-btn-medium">' + mw.lang('Cancel') + '</span>');
                footer.append(cancel);
                footer.append(ok);
                cancel.on('click', function () {
                    scope.dialog.remove();
                });
                ok.on('click', function () {
                    scope.dialog.remove();
                });
                scope.dialog = mw.dialogIframe({
                    url: mw.external_tool('link_editor_v2'),
                    autoHeight: true,
                    height: 'auto',
                    width: 700,
                    title: this.settings.dialogTitle,
                    footer: footer
                });
            }
        };
        this.init = function(){
            this.create();
        };
        this.init();
    },
    linkEditor: function (options) {
        return new this._linkEditor(options);
    }
};
