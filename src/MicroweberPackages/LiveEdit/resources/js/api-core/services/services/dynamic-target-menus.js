import MicroweberBaseClass from "../containers/base-class.js";

export class DynamicTargetMenus extends MicroweberBaseClass {

    constructor() {
        super();
    }

    addModuleQuickSetting (module, items) {

 
        if(!mw.quickSettings[module]) {
            mw.quickSettings[module] = [];
 
        }
        mw.app.liveEdit.moduleHandleContent.menu.setMenu('dynamic', mw.quickSettings[module])

    }

    addLayoutQuickSetting ( items ) {
 
            if(!mw.layoutQuickSettings){
                mw.layoutQuickSettings = [];
            }
            if(Array.isArray(items)) {
                for (let i = 0; i < items.length; i++) {
                    mw.layoutQuickSettings.push(items[i]);
                }
            } else {
                mw.layoutQuickSettings.push(items);
            }

    }

}
