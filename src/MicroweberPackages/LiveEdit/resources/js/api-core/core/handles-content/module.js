import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {DomService} from '../classes/dom.js';
import {Confirm} from "../classes/dialog";

export const ModuleHandleContent = function (rootScope) {
    var scope = this;
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-module-root',
            contentEditable: false,
        }
    });
    this.tools = DomService;




    var staticMenu = new HandleMenu({
        id: 'mw-handle-item-element-menu-default',
        title: 'Module',
        rootScope: rootScope,
        buttons: [
            {
                "title": "Settings",
                "icon": '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" /></svg>',
                action: () => {
                    const target = mw.app.get('liveEdit').handles.get('module').getTarget();

                    mw.app.editor.dispatch('onModuleSettingsRequest', target);
                    var type = target.dataset.type || target.getAttribute('type');
                    type = type.trim();
                    mw.app.editor.dispatch('onModuleSettingsRequest@' + type, target);
                },
                onTarget: function (target, selfNode) {

                    if(target.classList.contains('no-settings')) {
                        selfNode.style.display = 'none';
                    } else {
                        selfNode.style.display = '';
                    }
                },
            },
            {
                title: 'Insert module' ,
                text: '',
                icon: '<svg style="stroke-width: 500;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M445.935-195.935v-250h-250v-68.13h250v-250h68.13v250h250v68.13h-250v250h-68.13Z"/></svg>',
                className: 'mw-handle-add-button',
                onTarget: function (target, selfNode) {
                    if(scope.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfNode.style.display = '';
                    } else {
                        selfNode.style.display = 'none';
                    }
                },
                action: function (el) {
                    mw.app.editor.dispatch('insertModuleRequest', el);

                }
            },
            {
                "title": "Delete",
                "icon": '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"></path></svg>',
                action: () => {


                    Confirm(ElementManager('<span>Are you sure you want to delete this module?</span>'), () => {

                        const target = mw.app.get('liveEdit').handles.get('module').getTarget();
                        var type = target.dataset.type || target.getAttribute('type');
                        type = type.trim();
                        mw.app.registerChangedState(target)
                        target.remove()
                        mw.app.editor.dispatch('moduleRemoved', target);
                        mw.app.editor.dispatch('modulet@'+type+'Removed', target);
                        rootScope.moduleHandle.hide()
                    })






                },
                onTarget: (target, selfNode) => {


                    if(this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfNode.style.display = '';
                    } else {
                        selfNode.style.display = 'none';
                    }
                },
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

    this.staticMenu = staticMenu;
};

