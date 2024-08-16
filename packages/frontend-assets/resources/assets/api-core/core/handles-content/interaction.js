import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";

export const InteractionHandleContent = function (proto) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-interaction-item-element-root'
        }
    });
    
    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Element',
        buttons: [
 
        ],
    });

    this.menu.show()

    this.root.append(this.menu.root)


}

