import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {Confirm} from "../classes/dialog.js";

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
                props: { className: 'le-selectable-items-list-image', style: { backgroundImage: 'url(' + (data.icon || data.screenshot) + ')' }},

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


    const primaryNavigation = [
        {
            title: rootScope.lang('Settings'),
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M181.674-179.761h41.13l441.087-441.565-41.13-41.13-441.087 441.565v41.13Zm613.043-484.326L665.761-793.043l36.978-37.218q19.631-19.63 47.859-19.75 28.228-.119 47.859 19.272l37.782 37.782q18.435 18.196 17.837 44.153-.598 25.956-18.315 43.674l-41.044 41.043Zm-41.76 41.761L247.761-117.13H118.804v-128.957l504.957-504.956 129.196 128.717Zm-109.392-19.565-20.804-20.565 41.13 41.13-20.326-20.565Z"/></svg>',
            className: 'mw-handle-insert-button',

            menu: [
                {
                    title: rootScope.lang('Add something'),
                    text: rootScope.lang('Add something'),
                    icon: '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z" /></svg>',
                    className: 'mw-handle-insert-button',
                },
                {
                    title: rootScope.lang('Settings1212'),
                    text: 'Do alert',
                    className: 'mw-handle-insert-button',

                },
            ],
        },

        {
            title: rootScope.lang('Clone'),
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M178.087-70.717q-27.698 0-48.034-20.336t-20.336-48.034v-600.848h68.37v600.848h471.848v68.37H178.087Zm128.131-128.37q-27.599 0-47.865-20.266-20.266-20.266-20.266-47.865v-555.695q0-27.698 20.266-48.034t47.865-20.336h435.695q27.698 0 48.034 20.336t20.336 48.034v555.695q0 27.599-20.336 47.865-20.336 20.266-48.034 20.266H306.218Zm0-68.131h435.695v-555.695H306.218v555.695Zm0 0v-555.695 555.695Z"/></svg>',
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
                newEl.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
            }
        },

        {
            title: rootScope.lang('Move Down'),
            text: '',
            icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11,4H13V16L18.5,10.5L19.92,11.92L12,19.84L4.08,11.92L5.5,10.5L11,16V4Z" /></svg>',
            className: 'mw-handle-insert-button',
            onTarget: function (target, selfNode) {

                if(target.nextElementSibling === null) {
                    selfNode.style.display = 'none';
                } else {
                    selfNode.style.display = '';
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
                if (target.previousElementSibling === null) {
                    selfNode.style.display = 'none';
                } else {
                    selfNode.style.display = '';
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

                    rootScope.layoutHandle.set(target, true);
                    target.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
                }, 300)
            }
        },


        {
            title: rootScope.lang('Delete'),
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
            className: 'mw-handle-insert-button',
            action: function (target, selfNode, rootScope) {
                Confirm('Are you sure you want to delete this layout?', function (){
                    mw.app.registerChange(target)
                    target.remove()
                })
            }
        },


    ]

    
 

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
                name: 'primary',
                nodes: primaryNavigation
            }
        ],
    });

    this.menu.show();


    this.menusHolder.append(this.menu.root);


    this.root.append(this.menusHolder)

}

