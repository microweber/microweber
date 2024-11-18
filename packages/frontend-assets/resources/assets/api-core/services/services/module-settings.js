import MicroweberBaseClass from "../containers/base-class.js";


export class ModuleSettings extends MicroweberBaseClass {

    constructor() {
        super();

        this.moduleSettingsDialogIframeInstance = null;

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
                mw.app.canvas.getWindow().mw.reload_module('#' + $data.moduleId,function () {
                 //  onModuleReloaded is moved to api.js
                    //   mw.app.dispatch('onModuleReloaded', $data.moduleId);
                });
            }

            if (el2 !== null && el2 !== undefined) {
                mw.app.registerChangedState(el2);
                mw.app.canvas.getWindow().mw.reload_module('[data-module-id-from-preset="' + $data.moduleId + '"]',function () {
                 //    onModuleReloaded is moved to api.js
                    //   mw.app.dispatch('onModuleReloaded', $data.moduleId);
                });
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

        var moduleTypeOrig = moduleType;
        var moduleSettingsUrl = null;
        var moduleSettingsComponent = null;
        if(typeof mw !== 'undefined' && typeof mw.settings !== 'undefined' && typeof mw.settings.liveEditModuleSettingsUrls === 'object' && mw.settings.liveEditModuleSettingsUrls[moduleType]) {
            if (typeof mw.settings.liveEditModuleSettingsUrls === 'object' && mw.settings.liveEditModuleSettingsUrls[moduleType]) {
                moduleSettingsUrl = mw.settings.liveEditModuleSettingsUrls[moduleType];
            }
        }


        if(typeof mw !== 'undefined' && typeof mw.settings !== 'undefined' && typeof mw.settings.liveEditModuleSettingsComponents === 'object' && mw.settings.liveEditModuleSettingsComponents[moduleType]) {


            var liveEditIframeData = mw.top().app.canvas.getLiveEditData();



            moduleSettingsComponent = mw.settings.liveEditModuleSettingsComponents[moduleType];
                     var eventDetail = {
                        moduleType: moduleType,
                        moduleId: moduleId,
                        moduleSettingsComponent: moduleSettingsComponent,
                        params: attrsForSettings,
                     };

                    if(typeof liveEditIframeData !== 'undefined'){
                        eventDetail.liveEditIframeData = liveEditIframeData;
                    }

                    var event = new CustomEvent('openModuleSettingsAction', { detail: eventDetail });
                    window.dispatchEvent(event);
                    return;


        }
        moduleType = moduleType + '/admin';

        attrsForSettings.id = moduleId;
        attrsForSettings.type = moduleType;




        let modal = this.openSettingsModal(attrsForSettings, moduleId, modalTitle,moduleSettingsUrl);




        if (modal) {
            //on load
            modal.iframe.addEventListener('load', () => {
                var eventData = {
                    target: module,
                    modal: modal,
                    moduleType: moduleTypeOrig,
                    moduleId: moduleId,

                };
                if(modal.iframe.contentWindow && modal.iframe.contentWindow.document){
                    modal.iframe.contentWindow.document.addEventListener('mw-option-saved', function () {
                        mw.notification.success('Settings are saved');
                    });
                }

                mw.app.dispatch('moduleSettingsLoaded', eventData);


             });
        }
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
    openModuleSettingsById = function (moduleId) {

        const event = new CustomEvent('close-modal', {id: 'modal_id'});
        document.dispatchEvent(event)


        var target = mw.top().app.canvas.getWindow().mw.$('#' + moduleId)[0];
        if(target){
            mw.app.editor.dispatch('onModuleSettingsRequest', target);
        }
    }


    openSettingsModal(attrsForSettings, moduleId, modalTitle,settingsUrl) {

        // // hide handles
        // if(mw.top && mw.top().app.liveEdit.handles){
        //     mw.top().app.liveEdit.handles.get('layout').hide()
        //  }
        var canvasDocument = mw.top().app.canvas.getDocument();
        $(canvasDocument.body).addClass('mw-module-settings-modal-opened')



        attrsForSettings.live_edit = true;
        attrsForSettings.module_settings = true;

        attrsForSettings.iframe = true;
        attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;

        if(!settingsUrl){
            settingsUrl = route('live_edit.module_settings');
        }


        var src = settingsUrl + "?" + json2url(attrsForSettings);


        let dialogSettings = {
            // url: route('live_edit.module_settings') + '?id=' + moduleId+ '&type=' + moduleType+ '&live_edit=true',
            url: src,
            autoHeight:true,
            width: 370,
            height: 'auto',
            draggable: true,
            skin: 'mw_modal_simple mw_modal_live_edit_settings',
            overlayClose: () => {
                var checkForChanges = this.moduleCheckIfUserHasUnsavedChanges();
                if (checkForChanges) {
                    return mw.confirm(mw.lang("You have unsaved changes. Do you want to save them first") + '?',
                        function () {
                            return true;
                        }, function () {
                            moduleSettingsDialogIframe.remove();
                    });
                }
                return this.moduleCheckIfLiveweireStillLoading();
            },

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

        $(moduleSettingsDialogIframe).on('Hide', () => {
            $(canvasDocument.body).removeClass('mw-module-settings-modal-opened')
         });

        if (moduleSettingsDialogIframe.overlay) {
            moduleSettingsDialogIframe.overlay.style.backgroundColor = 'transparent';
        }
        this.moduleSettingsDialogIframeInstance = moduleSettingsDialogIframe;
        return moduleSettingsDialogIframe;
    }

    moduleCheckIfUserHasUnsavedChanges() {
        if (this.moduleSettingsDialogIframeInstance) {
            if (this.moduleSettingsDialogIframeInstance.iframe) {
                if (this.moduleSettingsDialogIframeInstance.iframe.contentWindow) {
                    var iframe = this.moduleSettingsDialogIframeInstance.iframe;
                    if(iframe && iframe.contentWindow && iframe.contentWindow.document && iframe.contentWindow.document.body){
                        var body = iframe.contentWindow.document.body;
                        if (body && body.classList.contains('mw-unsaved-changes')) {
                            return true;
                        }

                    }
                }
            }
        }

        return false;
    }

    moduleCheckIfLiveweireStillLoading() {
        if (this.moduleSettingsDialogIframeInstance) {
            if (this.moduleSettingsDialogIframeInstance.iframe) {
                if (this.moduleSettingsDialogIframeInstance.iframe.contentWindow) {
                    var iframe = this.moduleSettingsDialogIframeInstance.iframe;
                    //check if body has 'mw-livewire-loading' class
                    if(iframe && iframe.contentWindow && iframe.contentWindow.document && iframe.contentWindow.document.body){
                        var body = iframe.contentWindow.document.body;
                        if (body && body.classList.contains('mw-livewire-loading')) {
                             return false;
                        }

                    }
                }
            }
        }
        return true;

    }

}





