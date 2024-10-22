import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {DomService} from '../classes/dom.js';
import {Confirm} from "../classes/dialog.js";
import {HandleIcons} from "../handle-icons.js";
import liveEditHelpers from "../live-edit-helpers.service.js";
import { FreeElementActions } from "./free-element-actions.js";

export const moduleSettingsDispatch = function (target) {
    mw.app.editor.dispatch('onModuleSettingsRequest', target);
    var type = target.dataset.type || target.getAttribute('type');
    if (type) {
        type = type.trim();
        mw.app.editor.dispatch('onModuleSettingsRequest@' + type, target);
    } else {
        mw.log('moduleSettingsDispatch error : Module type is not defined');
    }
}



export class ModuleHandleContent {
    constructor(rootScope) {
        this.root = ElementManager({
            props: {
                id: 'mw-handle-item-module-root',
                contentEditable: false,
            }
        });
        this.tools = DomService;
        this.rootScope = rootScope;

        this.initMenu();

        this.menu.show();

        this.menusHolder = document.createElement('div');
        this.menusHolder.className = 'mw-handle-item-menus-holder';

        this.menusHolder.append(this.menu.root.get(0));
        this.root.append(this.menusHolder);
    }

    setMenuVisible(isVisible, node) {
        if (isVisible) {
            node.classList.remove('mw-le-handle-menu-button-hidden');
        } else {
            node.classList.add('mw-le-handle-menu-button-hidden');
        }

    }

    initMenu() {
        const handleIcons = new HandleIcons();

        const primaryMenu = [
            {
                title: 'Drag' ,
                text: '',
                icon: handleIcons.icon('drag'),
                className: 'mw-handle-drag-button mw-handle-drag-button-module',



            },
            {
                "title": "Settings",
                "icon": handleIcons.icon('settings'),
                action: () => {
                    const target = mw.app.liveEdit.handles.get('module').getTarget();
                    moduleSettingsDispatch(target);

                },
                onTarget: function (target, selfNode) {
                    var isInaccesible =  liveEditHelpers.targetIsInacesibleModule(target);
                    // hide setting if module is inaccesible
                    if (isInaccesible) {
                        selfNode.classList.add('mw-le-handle-menu-button-hidden');
                    } else {
                        selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                    }
                },
            },
            {
                title: 'Insert module' ,
                text: '',
                icon: handleIcons.icon('plus'),
                className: 'mw-handle-add-button',
                onTarget: function (target, selfNode) {
                    if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-hidden');
                    }
                },
                action: function (el) {
                    mw.app.editor.dispatch('insertModuleRequest', el);

                }
            },
        ];

        const tailMenuDelete = {
            "title": "Delete",
            "icon": handleIcons.icon('delete'),
            action: () => {


                mw.confirm('<span>Are you sure you want to delete this module?</span>', () => {

                    const target = mw.app.liveEdit.handles.get('module').getTarget();
                    var type = target.dataset.type || target.getAttribute('type');
                    type = type.trim();
                    mw.app.registerChangedState(target)
                    target.remove()
                    mw.app.editor.dispatch('moduleRemoved', target);
                    mw.app.editor.dispatch('modulet@'+type+'Removed', target);
                    this.rootScope.moduleHandle.hide()
                })


            },
            onTarget: (target, selfNode) => {


                if(this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {

                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },
        };

        const tailMenuFavorite = {
            "title": "Add to Favorites",
            "icon":  handleIcons.icon('favorite'),
            action: (el) => {

                if(el) {
                    mw.app.editor.dispatch('onModulePresetsRequest', el);
                }

            },
            onTarget: (target, selfNode) => {
                if(this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },
        };
    const tailMenuResetModule = {
            "title": "Reset Module",
            "icon":  handleIcons.icon('reset'),
            action: (el) => {

                var module_id = el.id;

                mw.top().app.canvas.getWindow().mw.tools.confirm_reset_module_by_id(module_id);

            },
            onTarget: (target, selfNode) => {
                if(this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['module'])) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },
        };

        const tailMenu = [
            {
                title: 'Quick Settings',
                icon: handleIcons.icon('more'),
                menu: [
                    {
                        name: 'Favorite',
                        nodes: [
                            tailMenuFavorite,

                            {
                                title: 'Bring to front',
                                text: '',
                                icon: handleIcons.icon('layer-up'),
                                className: 'mw-handle-settings-button',

                                action: (el) => {
                                   FreeElementActions.zIndexIncrement(  el);

                                },
                                onTarget: (target, selfBtn) => {
                                    var selfVisible = mw.top().app.freeDraggableElementTools.isFreeElement(target);

                                    this.setMenuVisible(selfVisible, selfBtn);

                                }
                            },
                            {
                                title: 'Bring to back',
                                text: '',
                                icon: handleIcons.icon('layer-down'),
                                className: 'mw-handle-settings-button',

                                action: (el) => {
                                    FreeElementActions.zIndexDecrement( el);

                                },
                                onTarget: (target, selfBtn) => {
                                    var selfVisible = mw.top().app.freeDraggableElementTools.isFreeElement(target);

                                    this.setMenuVisible(selfVisible, selfBtn);

                                }
                            },
                        ]
                    }, {
                        name: 'Reset Module',
                        nodes: [
                            tailMenuResetModule,
                        ]
                    },
                    {
                        name: 'Delete',
                        nodes: [
                            tailMenuDelete,
                        ]
                    },

                ]
            },
        ];

        this.menu = new HandleMenu({
            id: 'mw-handle-item-element-menu',
            title: 'Module',
            rootScope: this.rootScope,
            handleScope: this,
            menus: [
                {
                    name: 'primary',
                    nodes: primaryMenu
                },
                {
                    name: 'dynamic',
                    nodes: []
                },
                {
                    name: 'tail',
                    nodes: tailMenu
                }
            ]

        });
    }
}




