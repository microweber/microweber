import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {HandleIcons} from "../handle-icons";
import {ElementActions} from "./element-actions";

export const ElementHandleContent = function (proto) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-element-root'
        }
    });

    const handleIcons = new HandleIcons();
    const elementActions = new ElementActions(proto);


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

                elementActions.cloneElement(el);

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
                elementActions.moveBackward(el);
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
                elementActions.moveForward(el);
            }
        },
    ];
    const elementImageMenu = [
        {
            title: 'Reset Image Size',
            text: '',
            icon: handleIcons.icon('reset-image-size'),

            className: 'mw-handle-reset-image-button',

            action: function (el) {
                elementActions.resetImageSize(el);
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = false;

                const isImage = target.nodeName === 'IMG';
                if (isImage) {
                    selfVisible = true;
                    var hasSizes = target.style.width || target.style.height;
                    // if(hasSizes) {
                    // selfVisible = true;
                    //     }
                    selfVisible = true;
                }
                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },

    ];










    const elementLinkMenu = [
        {
            title: 'Link',
            text: '',
            icon: handleIcons.icon('link'),

            className: 'mw-handle-element-link-button',

            action: function (el) {
                elementActions.editLink(el);
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = false;

                const isImageOrLink = target.nodeName === 'IMG' || target.nodeName === 'A';
                if (isImageOrLink) {

                    selfVisible = true;

                }
                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },

        {
            title: 'Unlink',
            text: '',
            icon: handleIcons.icon('unlink'),

            className: 'mw-handle-element-unlink-button',

            action: function (el) {
                elementActions.removeLink(el);
            },
            onTarget: (target, selfBtn) => {
                var selfVisible = false;

                const isLinkOrParentWithLink = target.nodeName === 'A' || target.parentNode && target.parentNode.nodeName === 'A';
                if (isLinkOrParentWithLink) {
                    selfVisible = true;
                }
                selfBtn.style.display = selfVisible ? '' : 'none';
            },

        },

    ];

    const elementLinkMenuGroup = [
        {
            title: 'Link',
            icon: handleIcons.icon('link'),
            menu: elementLinkMenu

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
       ...elementLinkMenu,
       // ...elementLinkMenuGroup,
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
            icon: handleIcons.icon('more'),
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
            className: 'mw-handle-delete-button',
            action: function (el) {
                elementActions.deleteElement(el);
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
