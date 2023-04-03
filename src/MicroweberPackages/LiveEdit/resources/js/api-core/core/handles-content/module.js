import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";

export const ModuleHandleContent = function (rootScope) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-module-root',
            contentEditable: false,
        }
    });


 

    var staticMenu = new HandleMenu({
        id: 'mw-handle-item-element-menu-default',
        title: 'Module',
        rootScope: rootScope,
        buttons: [
            {
                "title": "Settings",
                "icon": '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" /></svg>',
                action: (target) => {
                    mw.app.editor.dispatch('onModuleSettingsRequest', target);
                    var type = target.dataset.type || target.getAttribute('type');
                    type = type.trim();
                    mw.app.editor.dispatch('onModuleSettingsRequest@' + type, target);
                }
            }
        ],
    })
    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Module',
        rootScope: rootScope,
        buttons: [
             
        ],
    });

    this.menu.show();
    staticMenu.show();

    this.root.append(this.menu.root);
    this.root.append(staticMenu.root);

};

