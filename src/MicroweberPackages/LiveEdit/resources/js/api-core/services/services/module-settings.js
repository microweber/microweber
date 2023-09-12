import MicroweberBaseClass from "../containers/base-class.js";


export class ModuleSettings extends MicroweberBaseClass {

    constructor() {
        super();

        mw.app.on('onLiveEditReady', event =>{
            this.init();
        });


    }

    init() {

        mw.app.editor.on('onModuleSettingsChanged', ($data) => {

            var el = mw.app.canvas.getWindow().$('#' + $data.moduleId)[0];
            var el2 = mw.app.canvas.getWindow().$('[data-module-id-from-preset="' + $data.moduleId + '"]')[0];
            if (el !== null) {
                mw.app.registerChangedState(el);
                mw.app.canvas.getWindow().mw.reload_module('#' + $data.moduleId);
            }

            if (el2 !== null && el2 !== undefined) {
                mw.app.registerChangedState(el2);
                mw.app.canvas.getWindow().mw.reload_module('[data-module-id-from-preset="' + $data.moduleId + '"]');
            }


        });

        mw.app.editor.on('onModuleSettingsRequest', module => this.moduleOrLayoutSettingsRequestHandle(module));
        mw.app.editor.on('onLayoutSettingsRequest', module => this.moduleOrLayoutSettingsRequestHandle(module, true));
        mw.app.editor.on('onModulePresetsRequest', module => this.moduleOrLayoutPresetsRequestHandle(module));


    }

    moduleOrLayoutSettingsRequestHandle(module, isLayout) {

        if (!isLayout && module) {
            isLayout = module.classList.contains('module-layouts')
        }

        if (isLayout) {
            mw.app.liveEdit.handles.get('layout').set(module)
            mw.app.liveEdit.handles.get('layout').position(module)
        } else {
            mw.app.liveEdit.handles.get('module').set(module)
            mw.app.liveEdit.handles.get('module').position(module)
        }


        var moduleId = module.id;
        var moduleIdFromPreset = module.getAttribute('data-module-id-from-preset');
        if(moduleIdFromPreset){
            // we are in preset
            moduleId = moduleIdFromPreset;
        }


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

        attrsForSettings.id = moduleId;
        attrsForSettings.type = moduleType;


        this.openSettingsModal(attrsForSettings, moduleId, modalTitle);
    }

    moduleOrLayoutPresetsRequestHandle(module, isLayout) {

        var moduleClone = module.cloneNode(true);

        var presetsModule = 'editor/module_presets';
        var moduleType = module.getAttribute('data-type');
        if (!moduleType) {
            moduleType = module.getAttribute('type');
        }
        var moduleIdForPreset = module.getAttribute('module-id-from-preset');


        if (isLayout) {
            //todo
        }

        var attributes = {};

        if (moduleClone && moduleClone.attributes) {
            $.each(moduleClone.attributes, function (index, attr) {
                attributes[attr.name] = attr.value;
            });
        }


        var attrsForSettings = attributes;
        attrsForSettings.module_type_for_preset = moduleType;
          attrsForSettings.module_id_for_preset = moduleIdForPreset;
        attrsForSettings.type = presetsModule;


        this.openSettingsModal(attrsForSettings, attrsForSettings.id, 'Module presets');


    }


    openSettingsModal(attrsForSettings, moduleId, modalTitle) {


        attrsForSettings.live_edit = true;
        attrsForSettings.module_settings = true;

        attrsForSettings.iframe = true;
        attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


        var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


        let dialogSettings = {
            // url: route('live_edit.module_settings') + '?id=' + moduleId+ '&type=' + moduleType+ '&live_edit=true',
            url: src,
            width: 320,
            height: 'auto',
            draggable: true,
            skin: 'mw_modal_simple mw_modal_live_edit_settings',
            overlayClose: true,
            title: modalTitle,
            id: 'module-quick-setting-dialog-' + moduleId
        };
        if (attrsForSettings['type'] && attrsForSettings['type'] === 'layouts/admin') {

            dialogSettings.position = {
                x: window.innerWidth - 400,
                y: 300
            };
        }

        let moduleSettingsDialogIframe = mw.dialogIframe(dialogSettings);

        if (moduleSettingsDialogIframe.overlay) {
            moduleSettingsDialogIframe.overlay.style.backgroundColor = 'transparent';
        }
    }
}





