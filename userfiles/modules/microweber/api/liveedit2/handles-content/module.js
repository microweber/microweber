import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";

export const ModuleHandleContent = function (rootScope) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-module-root',
            contentEditable: false,
        }
    });
    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Module',
        rootScope: rootScope,
        buttons: [],
    });

    this.menu.show();

    this.root.append(this.menu.root);

};

