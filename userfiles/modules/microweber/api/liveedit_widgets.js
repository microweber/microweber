mw.liveEditWidgets = {
    _cssEditorInSidebarAccordion : null,
    cssEditorInSidebarAccordion : function () {
        if(!this._cssEditorInSidebarAccordion){
            this._cssEditorInSidebarAccordion = mwd.createElement('iframe') ;
            this._cssEditorInSidebarAccordion.id = 'mw-css-editor-sidebar-iframe' ;
            this._cssEditorInSidebarAccordion.src = mw.external_tool('rte_css_editor');
            this._cssEditorInSidebarAccordion.scrolling = 'no';
            this._cssEditorInSidebarAccordion.frameBorder = 0;
            mwd.querySelector('#mw-css-editor-sidebar-iframe-holder').appendChild(this._cssEditorInSidebarAccordion);
            mw.tools.iframeAutoHeight(this._cssEditorInSidebarAccordion);
            this._cssEditorInSidebarAccordion.onload = function () {
                this.contentWindow.document.body.style.padding = 0;
                this.contentWindow.document.body.style.backgroundColor = 'transparent';
            };
        }
        return this._cssEditorInSidebarAccordion;
    },
    _tplSettings : null,
    loadTemplateSettings: function (url) {
        if (!this._tplSettings) {
            this._tplSettings = mwd.createElement('iframe') ;
            this._tplSettings.id = 'mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame' ;
            this._tplSettings.className = 'mw-live-edit-sidebar-settings-iframe' ;
            this._tplSettings.src = url;
            this._tplSettings.scrolling = 'no';
            this._tplSettings.frameBorder = 0;
            $('#mw-live-edit-sidebar-settings-iframe-holder-template-settings').html(this._tplSettings);
            mw.tools.iframeAutoHeight(this._tplSettings);
            this._tplSettings.onload = function () {
                this.contentWindow.document.querySelector('.mw-module-live-edit-settings').style.padding = 0;
            };
        }
        return this._tplSettings;
    }
};

mw.letools = {
    isLayout: function (node) {
        return (node.getAttribute('data-module-name') === 'layouts' || node.getAttribute('data-type') === 'layouts');
    }
};
