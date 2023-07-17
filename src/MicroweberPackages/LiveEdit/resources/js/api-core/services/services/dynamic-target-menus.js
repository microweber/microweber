import MicroweberBaseClass from "../containers/base-class.js";

export class DynamicTargetMenus extends MicroweberBaseClass {

    constructor() {
        super();
    }

    addModuleQuickSetting (module, items) {
        if(!mw.quickSettings[module]) {
            mw.quickSettings[module] = {};
           // console.warn(`${module} is not defined`)
          //  return
        }
        if(!mw.quickSettings[module].mainMenu){
            mw.quickSettings[module].mainMenu = [];
        }
        if(Array.isArray(items)) {
            for (let i = 0; i < items.length; i++) {
                mw.quickSettings[module].mainMenu.push(items[i]);
            }
        } else {
            mw.quickSettings[module].mainMenu.push(items);
        }

    }

}
