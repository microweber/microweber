import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {Confirm} from "../classes/dialog.js";
import { moduleSettingsDispatch } from "./module.js";
import { DomService } from "../classes/dom.js";

const _getModulesDataCache = {};

export const getModulesData = (u) => {
    return new Promise(resolve => {
       if(Array.isArray(u)) {
           resolve(u)
       } else if(typeof u === 'string') {
           if(_getModulesDataCache[u]) {
               resolve(_getModulesDataCache[u])
           } else {
               fetch(u, {mode: 'cors'}).then(res => res.json()).then(res => {
                   _getModulesDataCache[u] = res;
                   resolve( res )
               })
           }
       }
    });
}

const singleModuleItemRender = (data, type) => {
    const el = ElementManager({
        props: {
            className: 'le-selectable-items-list-item',
            moduleId: data.id,
        },
        content: [
            {
                props: {
                    className: 'le-selectable-items-list-image',
                    style: { backgroundImage: 'url(' + (data.icon || data.screenshot) + ')' }
                },

            },
            {
                props: {
                    className: 'le-selectable-items-list-title',
                    innerHTML: data.name,
                }
            }
        ]
    });

    el.get(0).__data = data

    return el;
}

const _loadModuleCache = {}

export const loadModule = (obj, endpoint) => {
    return new Promise(resolve => {
        if(!obj || (!obj.id && !obj.layout_file)){
            resolve(null);
            return;
        }
        const params = {
            ondrop: true,
            id: obj.id || 'module-' + Date.now()
        }
        if(obj.module) {
            params['data-module-name'] = obj.module;
        } else if(obj.type === 'layout') {
            params['data-module-name'] = 'layouts';
            params['template'] = obj.layout_file;
        }

        const conf = {
            method: 'POST',
            body: JSON.stringify(params),
            headers: {
                'Content-Type': 'application/json',
            }
        }


        fetch(endpoint, conf)
            .then(resp => resp.text())
            .then(resp => resolve(resp))


    })
}

export const modulesDataRender = (data, type) => {
    const el = ElementManager({
        props: {
            className: 'le-selectable-items-list le-selectable-items-list-type-' + type
        }
    });
    var cats = ElementManager({
        props: {
            className: 'le-selectable-items-list le-selectable-items-list-type-' + type
        }
    })

    data.forEach(function (item){
        el.append(singleModuleItemRender(item))
    })

    return el;
}

export const layoutSettingsDispatch = function (target) {
    mw.app.editor.dispatch('onLayoutSettingsRequest', target);

}

export const LayoutHandleContent = function (rootScope) {
    var scope = this;
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-layout-root'
        }
    });
    const _e = {};
    this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };


    const editNavigation = [
        {
            title: rootScope.lang('Settings'),
            text: 'Settings',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"/></svg>',
            className: 'mw-handle-button-wide mw-handle-edit-layout-button',
            action: function(target) {

                layoutSettingsDispatch(target);
            }

        }
    ];

    const primaryNavigation = [


        {
            title: rootScope.lang('Clone'),
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M178.087-70.717q-27.698 0-48.034-20.336t-20.336-48.034v-600.848h68.37v600.848h471.848v68.37H178.087Zm128.131-128.37q-27.599 0-47.865-20.266-20.266-20.266-20.266-47.865v-555.695q0-27.698 20.266-48.034t47.865-20.336h435.695q27.698 0 48.034 20.336t20.336 48.034v555.695q0 27.599-20.336 47.865-20.336 20.266-48.034 20.266H306.218Zm0-68.131h435.695v-555.695H306.218v555.695Zm0 0v-555.695 555.695Z"/></svg>',
            className: 'mw-handle-insert-button',
            onTarget: function(target, selfNode) {

                if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                    selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-disabled');
                }
            },
            action: function (target, selfNode, rootScope) {
                var el = document.createElement('div');
                el.innerHTML = target.outerHTML;
                ElementManager('[id]', el).each(function(){
                    this.id = 'le-id-' + new Date().getTime();
                });
                ElementManager(target).after(el.innerHTML);
                var newEl = target.nextElementSibling;
                mw.app.registerChange(target)
                mw.reload_module(newEl, function(){
                    mw.top().app.state.record({
                        target: mw.tools.firstParentWithClass(target, 'edit'),
                        value: parent.innerHTML
                    });
                });
                newEl.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
            }
        },

        {
            title: rootScope.lang('Presets'),
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg>',
            className: 'mw-handle-presets-button',
            action: (el) => {

                if(el) {
                    mw.app.editor.dispatch('onModulePresetsRequest', el);
                }



            },
            onTarget: (target, selfNode) => {
                if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                    selfNode.style.display = '';
                } else {
                    selfNode.style.display = 'none';
                }
            }

        },
        {
            title: rootScope.lang('Move Down'),
            text: '',
            icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11,4H13V16L18.5,10.5L19.92,11.92L12,19.84L4.08,11.92L5.5,10.5L11,16V4Z" /></svg>',
            className: 'mw-handle-insert-button',
            onTarget: function(target, selfNode) {
                if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module']) && target.nextElementSibling !== null) {
                    selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-disabled');
                }



            },
            action: function (target, selfNode) {

                var prev = target.nextElementSibling;
                if(!prev) return;
                var offTarget = target.getBoundingClientRect();
                var offPrev = prev.getBoundingClientRect();
                var to = 0;

                if (offTarget.top < offPrev.top) {
                    to = -(offTarget.top - offPrev.top)
                }

                target.classList.add("mw-le-target-to-animate")
                prev.classList.add("mw-le-target-to-animate")

                target.style.transform = 'translateY('+to+'px)';
                prev.style.transform = 'translateY('+(-to)+'px)';

                setTimeout(function (){
                    prev.parentNode.insertBefore(target, prev.nextSibling);
                    target.classList.remove("mw-le-target-to-animate")
                    prev.classList.remove("mw-le-target-to-animate")
                    target.style.transform = '';
                    prev.style.transform = '';
                    mw.app.registerChange(target)
                    target.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
                    rootScope.layoutHandle.set(target, true)
                }, 300)
            }

        },
        {
            title: rootScope.lang('Move up'),
            text: '',
            icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" /></svg>',
            className: 'mw-handle-insert-button',
            onTarget: function (target, selfNode, rootScope) {
                if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module']) && target.previousElementSibling !== null) {
                    selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-disabled');
                }
            },
            action: function (target, selfNode) {


                var prev = target.previousElementSibling;
                if(!prev) return;
                var offTarget = target.getBoundingClientRect();
                var offPrev = prev.getBoundingClientRect();
                var to = 0;

                if (offTarget.top > offPrev.top) {
                    to = -(offTarget.top - offPrev.top)
                }

                target.classList.add("mw-le-target-to-animate")
                prev.classList.add("mw-le-target-to-animate")

                target.style.transform = 'translateY('+to+'px)';
                prev.style.transform = 'translateY('+(-to)+'px)';

                setTimeout(function (){
                    prev.parentNode.insertBefore(target, prev);
                    target.classList.remove("mw-le-target-to-animate")
                    prev.classList.remove("mw-le-target-to-animate")
                    target.style.transform = '';
                    prev.style.transform = '';
                    mw.app.registerChange(target)
                    rootScope.layoutHandle.set(target, true);
                    target.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
                    rootScope.layoutHandle.set(target, true)
                }, 300)
            }
        },




    ];

    const tail = [
        {
            title: rootScope.lang('Delete'),
            text:  rootScope.lang('Delete'),
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
            className: 'mw-handle-button-wide mw-handle-layout-delete-button',

            onTarget: function(target, selfNode) {
                if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                    selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-disabled');
                }
            },

            action: function (target, selfNode, rootScope) {
                Confirm('Are you sure you want to delete this layout?', function (){
                    mw.app.registerChange(target)
                    target.remove()
                })
            }
        }
    ];




    this.addButtons = function (){

        var plusLabel = 'Add Layout';

        var handlePlus = which => {
            this.dispatch('insertLayoutRequest')
            this.dispatch('insertLayoutRequestOn' + which.charAt(0).toUpperCase() + which.slice(1))
        }

        this.plusTop = ElementManager({
            props: {
                className: 'mw-handle-item-layout-plus mw-handle-item-layout-plus-top',
                innerHTML: rootScope.lang(plusLabel)
            }
        });

        this.plusBottom = ElementManager({
            props: {
                className: 'mw-handle-item-layout-plus mw-handle-item-layout-plus-bottom',
                innerHTML: rootScope.lang(plusLabel)
            }
        });

        this.plusTop.on('click', function (){
            handlePlus('top')
        });
        this.plusBottom.on('click', function (){
            handlePlus('bottom')
        });

        this.root.append(this.plusTop)
        this.root.append(this.plusBottom)
    }


    this.addButtons()



    this.menusHolder = document.createElement('div');
    this.menusHolder.className = 'mw-handle-item-menus-holder';






    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Module',
        rootScope: rootScope,
        menus: [
            {
                name: 'editNavigation',
                nodes: editNavigation
            },
            {
                name: 'primary',
                nodes: primaryNavigation,
                holder: true,
            },
            {
                name: 'dynamic',
                nodes: []
            },
            {
                name: 'tail',
                nodes: tail
            }
        ],
    });

    this.menu.show();


    this.menusHolder.append(this.menu.root.get(0));


    this.root.append(this.menusHolder)

}

