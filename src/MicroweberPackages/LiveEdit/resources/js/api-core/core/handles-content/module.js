import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {DomService} from '../classes/dom.js';
import {Confirm} from "../classes/dialog";

export const moduleSettingsDispatch = function (target) {
    mw.app.editor.dispatch('onModuleSettingsRequest', target);
    var type = target.dataset.type || target.getAttribute('type');
    type = type.trim();
    mw.app.editor.dispatch('onModuleSettingsRequest@' + type, target);
}


export const ModuleHandleContent = function (rootScope) {
    var scope = this;
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-module-root',
            contentEditable: false,
        }
    });
    this.tools = DomService;

    const primaryMenu = [
        {
            "title": "Settings",
            "icon": '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"/></svg>',
            action: () => {
                const target = mw.app.get('liveEdit').handles.get('module').getTarget();
                moduleSettingsDispatch(target);
                
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
            icon: '<svg style="stroke-width: 500;" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M445.935-195.935v-250h-250v-68.13h250v-250h68.13v250h250v68.13h-250v250h-68.13Z"/></svg>',
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
    ];


    const tailMenu = [
        {
            "title": "Delete",
            "icon": '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
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
    ]

 

    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Module',
        rootScope: rootScope,
 
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

    this.menu.show();


    this.menusHolder = document.createElement('div');
    this.menusHolder.className = 'mw-handle-item-menus-holder';
 

    this.menusHolder.append(this.menu.root.get(0));
    this.root.append(this.menusHolder);

 
};

