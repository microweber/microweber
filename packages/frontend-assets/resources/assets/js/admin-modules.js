import BaseComponent from "../core/base-class.js";

export class AdminModules extends BaseComponent {

    getModuleSettingsUrl(moduleType,options = {}) {

        var srcBase = route('live_edit.module_settings');

        if (typeof mw !== 'undefined' && typeof mw.settings !== 'undefined' && typeof mw.settings.liveEditModuleSettingsUrls === 'object' && mw.settings.liveEditModuleSettingsUrls[moduleType]) {
            if (typeof mw.settings.liveEditModuleSettingsUrls === 'object' && mw.settings.liveEditModuleSettingsUrls[moduleType]) {
                srcBase = mw.settings.liveEditModuleSettingsUrls[moduleType];
            }
        }
        var src = srcBase;
        var attrsForSettings = {};
        if(options && typeof options === 'object' && Object.keys(options).length > 0) {
            src = srcBase + "?" + json2url(options);
        }

        return src;


    }
}
