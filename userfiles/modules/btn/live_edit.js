class BtnModuleSettings {

    /*onRegister() {

        mw.app.editor.on('onModuleSettingsRequest', (module) => {

            var moduleId = module.id;
                var moduleType = module.getAttribute('data-type');
                if (moduleType !== 'btn') {
                    return;
                }

                mw.dialogIframe({
                    url: route('live_edit.modules.settings.btn') + '?id=' + moduleId,
                    width: 300,
                    height: 500,
                    draggable: true,
                    template: 'mw_modal_simple',
                    title: 'Button settings',
                    id: 'btn-quick-setting-dialog-' + moduleId
                });

            }
        );

    }*/

}

mw.app.on('ready', () => {
   // mw.app.register('btn_module_settings', BtnModuleSettings);
});
