import MicroweberBaseClass from "../containers/base-class.js";


export class ModuleSettings extends MicroweberBaseClass {

    constructor() {
        super();
    }

    onRegister() {

        mw.app.editor.on('onModuleSettingsChanged', ($data) => {

            mw.app.canvas.getWindow().mw.reload_module('#' + $data.moduleId);


        });
        mw.app.editor.on('onModuleSettingsRequest', (module) => {



             var moduleId = module.id;
             var moduleType = module.getAttribute('data-type');
             if(!moduleType){
                 moduleType = module.getAttribute('type');
             }
            moduleType = moduleType+'/admin';
            mw.dialogIframe({
                url: route('live_edit.module_settings') + '?id=' + moduleId+ '&type=' + moduleType+ '&live_edit=true',
                width: 500,
                height: 500,
                draggable: true,
                template: 'mw_modal_simple',
                title: 'Module settings',
                id: 'btn-quick-setting-dialog-' + moduleId
            });





        });


    }


}





