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



            });


    }


}





