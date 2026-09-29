import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {Confirm} from "../classes/dialog.js";
import { DomService } from "../classes/dom.js";
import {LayoutActions} from "./layout-actions.js";

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


export class LayoutHandleContent {
    constructor(rootScope) {
        this.root = ElementManager({
            props: {
                id: 'mw-handle-item-layout-root',
            }
        });
        this.tools = DomService;
        this.rootScope = rootScope;
        this.events = {};

        this.initMenu();
        this.menu.show();

        this.menusHolder = document.createElement('div');
        this.menusHolder.className = 'mw-handle-item-menus-holder';

        this.menusHolder.append(this.menu.root.get(0));
        this.root.append(this.menusHolder);



        this.btnInsertModule = ElementManager(`<button type="button" class="insert-abs-module-button">Insert</button>`);
        this.btnInsertModule.on('click', function(){
            mw.app.editor.dispatch('insertFreeModuleRequest', mw.top().app.liveEdit.layoutHandle.getTarget().querySelector('.mw-layout-container'));


        });


        this.root.append(this.btnInsertModule);


        setTimeout(() => { this.addButtons() }, 100);
    }

    on(eventName, callback) {
        if (!this.events[eventName]) {
            this.events[eventName] = [];
        }
        this.events[eventName].push(callback);
    }

    dispatch(eventName, data) {
        if (this.events[eventName]) {
            this.events[eventName].forEach(callback => {
                callback.call(this, data);
            });
        }
    }

    initMenu() {

        let layoutHandleInstance = this;

        const layoutActions = new LayoutActions(this.rootScope);

        this.layoutActions = layoutActions;


        const editNavigation = [
            {
                // task (user request): the inline + inserts a LAYOUT (opens the
                // Insert-Layout picker, same as the ADD LAYOUT buttons). Sitting next
                // to "ADD LAYOUT", a + that inserted a *module* was misread as "add
                // layout", so it now actually adds a layout. Insert-module moved back
                // into the ⋮ dropdown (keeping its smart drop-container targeting).
                title: this.rootScope.lang('Add layout'),
                text: '',
                icon: '<svg fill="currentColor" height="24" viewBox="0 -960 960 960" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M440-120v-320H120v-80h320v-320h80v320h320v80H520v320h-80Z"/></svg>',
                className: 'mw-handle-layout-add-layout-button',
                action: function (target) {
                    mw.app.editor.dispatch('insertLayoutRequestOnBottom', target);
                }
            }
        ];

        // task (user request): collapse ALL block/layout actions into a single
        // ⋮ dropdown, leaving only an icon-only Settings button inline, so the
        // block handle never overflows the (mobile) viewport. Each action keeps
        // its icon and shows its label inside the dropdown (titleVisible:true).
        // Structure mirrors the module handle's "Quick Settings" pattern
        // (module.js): a tail node with `menu: [{ name, nodes }]` renders a
        // trigger button whose sub-menu holds these nodes.
        const dropdownNodes = [
            {
                // Insert-module is back in the dropdown. It drops the module INSIDE
                // the block's most suitable drop column: prefer an EMPTY .allow-drop
                // (the "Enter text or drop element" placeholder), else the
                // least-crowded column, else the first; fall back to the block itself.
                title: this.rootScope.lang('Insert module'),
                titleVisible: true,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 -960 960 960" fill="currentColor"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520Z"/></svg>',
                className: 'mw-handle-layout-insert-module-button',
                onTarget: function(target, selfNode) {
                    if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-disabled');
                    }
                },
                action: function (target) {
                    var dropContainer = null;
                    if (target && target.querySelectorAll) {
                        var drops = Array.prototype.slice.call(target.querySelectorAll('.allow-drop'));
                        if (drops.length) {
                            var moduleCount = function (d) { return d.querySelectorAll('.module').length; };
                            dropContainer = drops.filter(function (d) { return moduleCount(d) === 0; })[0];
                            if (!dropContainer) {
                                dropContainer = drops.slice().sort(function (a, b) {
                                    return moduleCount(a) - moduleCount(b);
                                })[0];
                            }
                        }
                    }
                    mw.app.editor.dispatch('insertModuleRequest', dropContainer || target);
                }
            },
            {
                // Settings lives in the dropdown now (the inline slot is the + button).
                title: this.rootScope.lang('Settings'),
                titleVisible: true,
                text: '',
                icon: mw.top().app.iconService.icon('settings'),
                className: 'mw-handle-layout-settings-button',
                action: function(target) {
                    layoutSettingsDispatch(target);
                }
            },
            {
                title: this.rootScope.lang('Clone'),
                titleVisible: true,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M178.087-70.717q-27.698 0-48.034-20.336t-20.336-48.034v-600.848h68.37v600.848h471.848v68.37H178.087Zm128.131-128.37q-27.599 0-47.865-20.266-20.266-20.266-20.266-47.865v-555.695q0-27.698 20.266-48.034t47.865-20.336h435.695q27.698 0 48.034 20.336t20.336 48.034v555.695q0 27.599-20.336 47.865-20.336 20.266-48.034 20.266H306.218Zm0-68.131h435.695v-555.695H306.218v555.695Zm0 0v-555.695 555.695Z"/></svg>',
                className: 'mw-handle-layout-clone-button',
                onTarget: function(target, selfNode) {

                    if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-disabled');
                    }
                },
                action: function (target, selfNode, rootScope) {
                    layoutActions.cloneLayout(target);
                }
            },
            {
                title: this.rootScope.lang('Presets'),
                titleVisible: true,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg>',
                className: 'mw-handle-presets-button',
                action: (el) => {

                    if(el) {
                        mw.app.editor.dispatch('onModulePresetsRequest', el);
                    }



                },
                onTarget: (target, selfNode) => {
                    const enabled = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module']);
                    selfNode.classList[!enabled ? 'add' : 'remove']('mw-le-handle-menu-button-disabled');


                }

            },
            {
                title: this.rootScope.lang('Move Down'),
                titleVisible: true,
                text: '',
                icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path d="M11,4H13V16L18.5,10.5L19.92,11.92L12,19.84L4.08,11.92L5.5,10.5L11,16V4Z" /></svg>',
                className: 'mw-handle-layout-move-down-button',
                onTarget: function(target, selfNode) {
                    if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module']) && target.nextElementSibling !== null) {
                        selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-disabled');
                    }

                },
                action: function (target, selfNode) {

                    layoutActions.moveDown(target)
                }

            },
            {
                title: this.rootScope.lang('Move up'),
                titleVisible: true,
                text: '',
                icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" /></svg>',
                className: 'mw-handle-layout-move-up-button',
                onTarget: function (target, selfNode, rootScope) {
                    if(DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module']) && target.previousElementSibling !== null) {
                        selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-disabled');
                    }
                },
                action: function (target, selfNode) {

                    layoutActions.moveUp(target)
                }
            },
            {
                title: this.rootScope.lang('Delete'),
                titleVisible: true,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
                className: 'mw-handle-layout-delete-button',
                onTarget: function(target, selfNode) {
                    let selfVisible = true;


                    if(!DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
                        selfVisible = false;
                    }
                    if(selfVisible) {

                        selfNode.classList.remove('mw-le-handle-menu-button-disabled');
                    } else {
                        selfNode.classList.add('mw-le-handle-menu-button-disabled');
                    }
                },

                action: function (target, selfNode, rootScope) {
                    layoutActions.deleteLayout(target);
                }
            }
        ];

        const primaryNavigation = [];

        // task-2026-09-29-layoutmods — keep a reference to the static action
        // nodes so refreshInnerModules() can rebuild the ⋮ dropdown per target
        // (static layout actions + the layout's live inner-module list).
        this.dropdownNodes = dropdownNodes;

        const tail = [
            {
                title: this.rootScope.lang('More'),
                icon: '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/></svg>',
                className: 'mw-handle-layout-more-button',
                menu: [
                    {
                        name: 'layoutActions',
                        nodes: dropdownNodes
                    }
                ]
            }
        ];

        // Keep a handle to the ⋮ "More" node so refreshInnerModules() can graft
        // the inner-module group onto its submenu as the target layout changes.
        this.tailNode = tail[0];
        this.menu = new HandleMenu({
            id: 'mw-handle-item-element-menu-layout',
            title: 'Module',
            rootScope: this.rootScope,
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
                    name: 'layouts$teleportmenu',
                    nodes: [],
                    holder: true
                },
                {
                    name: 'tail',
                    nodes: tail
                }
            ],
        });
    }

    // task-2026-09-29-layoutmods — collect the editable, accessible modules a
    // layout contains so the ⋮ dropdown can list them. Uses the SAME detection
    // as the Layout Settings modal (getTargets in layouts-module-settings.js):
    // ALL descendant .module elements, not just direct children. The strict
    // direct-child version missed modules in layouts that wrap them in an extra
    // container (e.g. the site header, which lives OUTSIDE .edit) — the modal
    // showed those modules but the handle did not. Matching the modal keeps the
    // two surfaces consistent.
    getLayoutInnerModules(layoutElement) {
        const result = [];
        if (!layoutElement || !layoutElement.querySelectorAll) {
            return result;
        }
        const excluded = ['layouts', 'layout', 'text', 'spacer', 'divider'];
        const all = layoutElement.querySelectorAll(
            '.module[data-type]:not([data-type=""]):not(.module-layouts)'
        );
        const seen = new Set();
        all.forEach((moduleEl) => {
            // Skip inaccessible modules (same helper the rest of Live Edit uses).
            try {
                const helpers = mw.top().app.liveEdit &&
                    mw.top().app.liveEdit.liveEditHelpers;
                if (helpers && helpers.targetIsInacesibleModule &&
                    helpers.targetIsInacesibleModule(moduleEl)) {
                    return;
                }
            } catch (e) { /* non-fatal */ }

            const type = moduleEl.getAttribute('data-type') ||
                moduleEl.getAttribute('type');
            if (!type || excluded.indexOf(type.toLowerCase()) !== -1) {
                return;
            }

            // De-dupe by element id so the same module can't appear twice.
            const key = moduleEl.id || (type + '::' + result.length);
            if (seen.has(key)) { return; }
            seen.add(key);

            let title = type;
            let icon = '';
            try {
                const modules = mw.top().app.modules;
                if (modules) {
                    const info = modules.getModuleInfo(type);
                    if (info && info.name) { title = info.name; }
                    icon = modules.getModuleIcon(type) || '';
                }
            } catch (e) { /* registry not ready — fall back to type */ }

            result.push({ element: moduleEl, type, title, icon });
        });

        return result.slice(0, 12);
    }

    // Build ⋮-dropdown menu nodes for the layout's inner modules. Each node
    // captures its own module element and opens that module's settings
    // (quick-settings-first, falling back to the main settings form).
    buildInnerModuleNodes(layoutElement) {
        const fallbackIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" height="24" width="24" fill="currentColor"><path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520Z"/></svg>';
        return this.getLayoutInnerModules(layoutElement).map((mod) => {
            const moduleEl = mod.element;
            return {
                title: mod.title,
                titleVisible: true,
                text: '',
                icon: mod.icon || fallbackIcon,
                className: 'mw-handle-layout-inner-module-button',
                action: function () {
                    try {
                        if (moduleEl && moduleEl.scrollIntoView) {
                            moduleEl.scrollIntoView({ block: 'center' });
                        }
                    } catch (e) { /* non-fatal */ }
                    mw.app.editor.dispatch(
                        'onModuleQuickSettingsOrMainSettingsRequest',
                        moduleEl
                    );
                }
            };
        });
    }

    // Rebuild the ⋮ "More" dropdown for the current target: static layout
    // actions first, then a grouped list of the modules this layout contains.
    refreshInnerModules(target) {
        if (!this.tailNode || !this.menu) {
            return;
        }
        const moduleNodes = this.buildInnerModuleNodes(target);
        const submenu = [
            { name: 'layoutActions', nodes: this.dropdownNodes }
        ];
        if (moduleNodes.length) {
            // NB: do NOT wrap these in a `holder: true` group. handles.scss hides
            // every `.mw-le-handle-menu-button-holder` inside the layout handle for
            // header/footer layouts ([data-header-footer="true"]), which silently
            // hid the modules there. Adding them as plain nodes keeps them visible
            // on header/footer too (they render like the always-visible Settings).
            submenu.push({
                name: 'layoutModules',
                nodes: moduleNodes
            });
        }
        this.tailNode.menu = submenu;
        this.menu.setMenu('tail', [this.tailNode]);
    }

    positionButtons(target) {


        const _hideButtons = () => {
            if(this.plusTop){
                this.plusTop.css({
                    left: -9999,
                    top: -9999,
                    zIndex: 1102,
                });
            }
            if(this.plusBottom) {
                this.plusBottom.css({
                    left: -9999,
                    top: -9999,
                    zIndex: 1102,
                });
            }
        }
        if(!target) {
            _hideButtons()
            return;
        }
        if(!DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target.parentNode, ['edit', 'module'])) {
            _hideButtons()

            return;
        }
        const targetDocument = mw.top().app.canvas.getDocument()
        const off = ElementManager(target, targetDocument).offset();

        if(off === null) {
            _hideButtons()
            return;
        }
        if(this.plusTop) {
            this.plusTop.css({
                left: off.offsetLeft + (off.width / 2),
                top: off.offsetTop,
                zIndex: 1102,
            });
        }
        if(this.plusBottom) {
            this.plusBottom.css({
                left: off.offsetLeft + (off.width / 2),
                top: off.offsetTop + target.offsetHeight - 15,
                zIndex: 1102,
            });
        }


    }

    addButtons() {
        var plusLabel = mw.lang('Add Layout');
        plusLabel = mw.top().app.iconService.icon('plus', {width: '20px', fill: 'currentColor'}) + ' ' + plusLabel.toUpperCase();
        const handlePlus = which => {
            this.dispatch('insertLayoutRequest');
            this.dispatch('insertLayoutRequestOn' + which.charAt(0).toUpperCase() + which.slice(1));
        };

        this.plusTop = ElementManager({
            props: {
                className: 'mw-handle-item-layout-plus mw-handle-item-layout-plus-top',
                innerHTML: this.rootScope.lang(plusLabel),
                tabIndex: 0
            }
        });
        this.plusTop.attr('role', 'button').attr('aria-label', 'Add layout above section');

        this.plusBottom = ElementManager({
            props: {
                className: 'mw-handle-item-layout-plus mw-handle-item-layout-plus-bottom',
                innerHTML: this.rootScope.lang(plusLabel),
                tabIndex: 0
            }
        });
        this.plusBottom.attr('role', 'button').attr('aria-label', 'Add layout below section');

        /* task-2026-05-28-8a32a5 / AI-1219: keydown Enter/Space operability */
        const handleKeydown = (which) => (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handlePlus(which);
            }
        };

        this.plusTop.hide().on('click', () => {
            handlePlus('top');
        }).on('keydown', handleKeydown('top'));

        this.plusBottom.hide().on('click', () => {
            handlePlus('bottom');
        }).on('keydown', handleKeydown('bottom'));

        const targetDocument = mw.top().app.canvas.getDocument()

        targetDocument.body.append(this.plusTop.get(0));
        targetDocument.body.append(this.plusBottom.get(0));



        mw.top().app.liveEdit.handles.get('layout').on('hide', () => {

            this.plusTop.hide()
            this.plusBottom.hide()
        });


        mw.top().app.liveEdit.handles.get('layout').on('show', () => {

            this.plusTop.show()
            this.plusBottom.show()
        })
    }
}
