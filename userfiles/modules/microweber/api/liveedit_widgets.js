mw.liveEditWidgets = {
    _cssEditorInSidebarAccordion : null,
    cssEditorInSidebarAccordion : function () {
        if(!this._cssEditorInSidebarAccordion){
            this._cssEditorInSidebarAccordion = mwd.createElement('iframe') ;
            this._cssEditorInSidebarAccordion.id = 'mw-css-editor-sidebar-iframe' ;
            this._cssEditorInSidebarAccordion.src = mw.external_tool('rte_css_editor');
            this._cssEditorInSidebarAccordion.frameBorder = 0;
            mwd.querySelector('#mw-css-editor-sidebar-iframe-holder').appendChild(this._cssEditorInSidebarAccordion);
            this._cssEditorInSidebarAccordion.onload = function () {
                this.contentWindow.document.body.style.padding = 0;
            };
        }
        return this._cssEditorInSidebarAccordion;
    }
};
