mw.liveEditWidgets = {
    _cssEditorInSidebarAccordion : null,
    cssEditorInSidebarAccordion : function () {
        if(!this._cssEditorInSidebarAccordion){
            this._cssEditorInSidebarAccordion = document.createElement('iframe') ;
            this._cssEditorInSidebarAccordion.id = 'mw-css-editor-sidebar-iframe' ;
            this._cssEditorInSidebarAccordion.src = mw.external_tool('rte_css_editor2');
           // this._cssEditorInSidebarAccordion.style.opacity = 0;
            this._cssEditorInSidebarAccordion.scrolling = 'no';
            this._cssEditorInSidebarAccordion.frameBorder = 0;
            var holder = document.querySelector('#mw-css-editor-sidebar-iframe-holder');
            holder.appendChild(this._cssEditorInSidebarAccordion);
           // mw.tools.loading(holder, 90);
            mw.tools.iframeAutoHeight(this._cssEditorInSidebarAccordion);
            // this._cssEditorInSidebarAccordion.onload = function () {
            //     this.contentWindow.document.body.style.padding = 0;
            //     this.contentWindow.document.body.style.backgroundColor = 'transparent';
            //     mw.tools.loading(holder, false);
            //     this.style.opacity = 1;
            // };
            // mw.$(this._cssEditorInSidebarAccordion)
            //     .height($(this._cssEditorInSidebarAccordion)
            //         .parents('.mw-ui-box').outerHeight() -
            //         mw.$(this._cssEditorInSidebarAccordion).parents('.tabitem').outerHeight());
        }
        return this._cssEditorInSidebarAccordion;
    },
    _tplSettings : null,
    loadTemplateSettings: function (url) {
        if (!this._tplSettings) {
            this._tplSettings = document.createElement('iframe') ;
            this._tplSettings.id = 'mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame' ;
            this._tplSettings.className = 'mw-live-edit-sidebar-settings-iframe' ;
            this._tplSettings.src = url;
            this._tplSettings.scrolling = 'no';
            this._tplSettings.frameBorder = 0;
            mw.$('#mw-live-edit-sidebar-settings-iframe-holder-template-settings').html(this._tplSettings);
            mw.tools.iframeAutoHeight(this._tplSettings);
            this._tplSettings.onload = function () {
                this.contentWindow.document.querySelector('.mw-module-live-edit-settings').style.padding = 0;
            };
            mw.$(this._tplSettings)
                .height($(this._tplSettings)
                        .parents('.mw-ui-box').outerHeight() -
                    mw.$(this._tplSettings).parents('.tabitem').outerHeight());
        }
        return this._tplSettings;
    }
};

mw.liveEditTools = {
    isLayout: function (node) {
        return (!!node && !!node.getAttribute && (node.getAttribute('data-module-name') === 'layouts' || node.getAttribute('data-type') === 'layouts'));
    }
};
