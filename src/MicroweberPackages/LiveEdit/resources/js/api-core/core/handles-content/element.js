import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {Confirm} from "../classes/dialog.js";
import {HandleIcons} from "../handle-icons";

export const ElementHandleContent = function (proto) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-element-root'
        }
    });

    const handleIcons = new HandleIcons();


    const cloneAbleMenu = [
        {
            title: 'Duplicate',
            text: '',
            icon: handleIcons.icon('duplicate'),
            className: 'mw-handle-clone-button',
            onTarget: function (target, selfNode) {


                if (target.classList.contains('cloneable') || target.classList.contains('mw-col')) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },


            action: function (el) {

                //check if is IMG and cloneable is in A tag
                if(el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
                    el = el.parentNode;
                }

                ElementManager(el).after(el.outerHTML);
                var next = el.nextElementSibling;
                if (el.classList.contains('mw-col')) {
                    el.style.width = ''
                    next.style.width = ''
                }

                proto.elementHandle.set(el);
                mw.app.registerChangedState(el)
            }
        },
        {
            title: 'Move backward',
            text: '',
            icon: handleIcons.icon('move-backward'),
            className: 'mw-handle-move-back-button',
            onTarget: function (target, selfNode) {
                const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
                const prev = target.previousElementSibling;


                if (isCloneable && prev) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },
            action: function (el) {
                const prev = el.previousElementSibling;
                if (prev) {
                    prev.before(el);
                    proto.elementHandle.set(el);
                    mw.app.registerChangedState(el)
                }
            }
        },
        {
            title: 'Move forward',
            text: '',
            icon: handleIcons.icon('move-forward'),

            className: 'mw-handle-move-back-button',
            onTarget: function (target, selfNode) {
                const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
                const next = target.nextElementSibling;


                if (isCloneable && next) {
                    selfNode.classList.remove('mw-le-handle-menu-button-hidden');
                } else {
                    selfNode.classList.add('mw-le-handle-menu-button-hidden');
                }
            },
            action: function (el) {
                const next = el.nextElementSibling;

                if (next) {
                    next.after(el);
                    proto.elementHandle.set(el);
                    mw.app.registerChangedState(el)
                }

            }
        },
    ];
    const elementImageMenu = [
        {
            title: 'Reset Image',
            text: '',
            icon: handleIcons.icon('reset-image-size'),

            className: 'mw-handle-reset-image-button',

            action: function (el) {
                mw.app.registerUndoState(el);
                el.style.width = '';
                el.style.height = '';
                mw.app.registerChangedState(el);
                proto.elementHandle.set(el);
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = false;

                const isImage = target.nodeName === 'IMG';
                if (isImage) {
                    selfVisible = true;
                    var hasSizes = target.style.width || target.style.height;
                    if(hasSizes) {
                    selfVisible = true;
                        }
                }
                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },

    ];


    const primaryMenu = [
        {
            title: 'Drag',
            text: '',
            icon: handleIcons.icon('drag'),
            className: 'mw-handle-drag-button mw-handle-drag-button-element',
            action: () => {
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = true;
                const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
                if (isCloneable) {
                    selfVisible = false;
                }

                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },

        {
            title: 'Edit',
            text: '',
            icon: handleIcons.icon('edit'),

            className: 'mw-handle-add-button',

            action: function (el) {
                mw.app.editor.dispatch('editNodeRequest', el);
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = true;

                const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
                if (isCloneable) {
                    selfVisible = false;
                }

                if (target.classList.contains('spacer')) {
                    selfVisible = false;
                }
                selfVisible = true;
                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },


        {
            title: 'Insert module',
            text: '',
            icon: handleIcons.icon('plus'),
            className: 'mw-handle-add-button',

            action: function (el) {

                mw.app.editor.dispatch('insertModuleRequest', el);

            }
        },
        {
            title: 'Settings',
            text: '',
            icon: handleIcons.icon('settings'),
            className: 'mw-handle-insert-button',

            action: function (el) {
                mw.app.editor.dispatch('elementSettingsRequest', el);

            },
            onTarget: function (target, selfBtn) {
                var selfVisible = true;

                const isCloneable = target.classList.contains('cloneable') || target.classList.contains('mw-col');
                if (isCloneable) {
                    selfVisible = false;
                }

                if (target.classList.contains('spacer')) {
                    selfVisible = false;
                }
                selfBtn.style.display = selfVisible ? '' : 'none';
            }
        },

        // ...cloneAbleMenu,
        //  ...elementImageMenu,


    ]

    const tailMenuQuickSettings = [
        {
            title: 'Quick Settings',
            icon:  handleIcons.icon('more'),
            menu: [
                {
                    name: 'Cloneable',

                    nodes: cloneAbleMenu,

                },
                {
                    name: 'Image settings',
                    nodes:
                    elementImageMenu

                },
            ]
        },
    ];


    const tail = [
        {
            title: proto.lang('Delete'),
            text: '',
            icon: handleIcons.icon('delete'),
            className: 'mw-handle-insert-button',
            action: function (el) {

                //check if is IMG and is in A tag
                if(el.nodeName === 'IMG' && el.parentNode && el.parentNode.nodeName === 'A') {
                    el = el.parentNode;
                }

                Confirm(ElementManager('<span>Are you sure you want to delete this element?</span>'), () => {
                    mw.app.registerChangedState(el)
                    el.remove()
                    proto.elementHandle.hide()
                })
            }
        }
    ]

    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Element',
        handleScope: this,

        menus: [
            {
                name: 'primary',
                nodes: primaryMenu
            },
            {
                name: 'dynamic',
                nodes: []
            }, {
                name: 'tailMenuQuickSettings',
                nodes: tailMenuQuickSettings
            },
            {
                name: 'tail',
                nodes: tail
            }
        ],

    });

    this.menu.show()


    this.menusHolder = document.createElement('div');
    this.menusHolder.className = 'mw-handle-item-menus-holder';


    var holder = mw.element(this.menusHolder);

    holder.append(this.menu.root);


    this.root.append(this.menusHolder);


}
