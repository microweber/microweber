import MicroweberBaseClass from "../containers/base-class.js";


export class ModuleSettings extends MicroweberBaseClass {

    constructor() {
        super();


    }

    onRegister() {

        mw.app.editor.on('onModuleSettingsChanged', ($data) => {

            mw.app.canvas.getWindow().mw.reload_module('#' + $data.moduleId);


        });
 
        mw.app.editor.on('onModuleSettingsRequest', module => this.moduleOrLayoutSettingsRequestHandle(module));
        mw.app.editor.on('onLayoutSettingsRequest', module => this.moduleOrLayoutSettingsRequestHandle(module, true));
        mw.app.editor.on('showModulePresetsRequest', module => this.showModulePresetsRequest(module, true));


    }
    moduleOrLayoutSettingsRequestHandle(module, isLayout) {

        if (isLayout) {
            mw.app.liveEdit.handles.get('layout').set(module)
            mw.app.liveEdit.handles.get('layout').position(module)
        } else {
            mw.app.liveEdit.handles.get('module').set(module)
            mw.app.liveEdit.handles.get('module').position(module)
        }


        var moduleId = module.id;
        var moduleType = module.getAttribute('data-type');
        if (!moduleType) {
            moduleType = module.getAttribute('type');
        }

        var modalTitle = 'Module settings';
        var modalTitleFromAttr = module.getAttribute('data-mw-title');
        if (modalTitleFromAttr) {
            modalTitle = modalTitleFromAttr;
        }

        var el = module;


        var attributes = {};

        if (el && el.attributes) {
            $.each(el.attributes, function (index, attr) {
                attributes[attr.name] = attr.value;
            });
        }


        var attrsForSettings = attributes;

        if (attrsForSettings['data-module-name'] !== undefined) {
            delete (attrsForSettings['data-module-name']);
        }
        if (typeof attrsForSettings['class'] !== 'undefined') {
            delete (attrsForSettings['class']);
        }

        if (typeof attrsForSettings['data-type'] !== 'undefined') {
            delete (attrsForSettings['data-type']);
        }
        if (typeof attrsForSettings['style'] !== 'undefined') {
            delete (attrsForSettings['style']);
        }
        if (typeof attrsForSettings.contenteditable !== 'undefined') {
            delete (attrsForSettings.contenteditable);
        }


        moduleType = moduleType + '/admin';


        attrsForSettings.live_edit = true;
        attrsForSettings.module_settings = true;
        attrsForSettings.id = moduleId;
        attrsForSettings.type = moduleType;
        attrsForSettings.iframe = true;
        attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


        var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


        let moduleSettingsDialogIframe = mw.dialogIframe({
            // url: route('live_edit.module_settings') + '?id=' + moduleId+ '&type=' + moduleType+ '&live_edit=true',
            url: src,
            width: 320,
            height: 'auto',
            draggable: true,
            skin: 'mw_modal_simple mw_modal_live_edit_settings',
            overlayClose: true,
            title: modalTitle,
            id: 'module-quick-setting-dialog-' + moduleId
        });


        if (moduleSettingsDialogIframe.overlay) {
            moduleSettingsDialogIframe.overlay.style.backgroundColor = 'transparent';
        }


    }
    showModulePresetsRequest(module, isLayout) {
         alert(3333);
    }
}





