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
        buttons: [
            {
                title: rootScope.lang('Clone1111'),
                text: '',
                icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" /></svg>',
                className: 'mw-handle-insert-button',
                action: function (target, selfNode, rootScope) {
                    var el = document.createElement('div');
                    el.innerHTML = target.outerHTML;
                    ElementManager('[id]', el).each(function(){
                        this.id = 'le-id-' + new Date().getTime();
                    });
                    ElementManager(target).after(el.innerHTML);
                    var newEl = target.nextElementSibling;
                    mw.reload_module(newEl, function(){
                        rootScope.statemanager.record({
                            target: mw.tools.firstParentWithClass(target, 'edit'),
                            value: parent.innerHTML
                        });
                    });
                }
            },
        ],
    });

    this.menu.show();

    this.root.append(this.menu.root);

};

