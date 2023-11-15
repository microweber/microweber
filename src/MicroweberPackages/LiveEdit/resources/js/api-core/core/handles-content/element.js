import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {HandleIcons} from "../handle-icons";
import {ElementActions} from "./element-actions";
import {DomService} from "../classes/dom";
import {ElementSettingsTarget} from "./element-settings-target";
import {ElementHandleButtonsVisibility} from "./element-handle-buttons-visibility";


const isPlaceholder = target => target.classList.contains('mw-img-placeholder');


export class ElementHandleContent {
    constructor(rootScope) {


        this.root = ElementManager({
            props: {
                id: 'mw-handle-item-element-root'
            }
        });

        this.tools = DomService;
        this.rootScope = rootScope;
        this.settingsTarget = new ElementSettingsTarget(this.rootScope);


        this.initMenu();


        this.menu.show();


        this.menusHolder = document.createElement('div');
        this.menusHolder.className = 'mw-handle-item-menus-holder';


        var holder = mw.element(this.menusHolder);

        holder.append(this.menu.root);


        this.root.append(this.menusHolder);


    }

    initMenu() {

        const handleIcons = new HandleIcons();
        const elementActions = new ElementActions(this.rootScope);
        const elementHandleButtonsVisibility = new ElementHandleButtonsVisibility(this.rootScope);


        const cloneAbleMenu = [
            {
                title: 'Duplicate',
                text: '',
                icon: handleIcons.icon('duplicate'),
                className: 'mw-handle-clone-button',
                onTarget: function (target, selfNode) {

                    const isVisible = elementHandleButtonsVisibility.shouldShowCloneButton(target);

                    if (isVisible) {
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

                    const isVisible = elementHandleButtonsVisibility.shouldShowMoveBackwardButton(target);

                    if (isVisible) {
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

                    const isVisible =  elementHandleButtonsVisibility.shouldShowMoveForwardButton(target);

                    if (isVisible) {
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
        const elementResetImageSizeMenu = [
            {
                title: 'Reset Image Size',
                text: '',
                icon: handleIcons.icon('reset-image-size'),

                className: 'mw-handle-reset-image-button',

                action: function (el) {
                    elementActions.resetImageSize(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowResetImageSizeButton(target);

                    selfBtn.classList[selfVisible ? 'remove' : 'add']('mw-le-handle-menu-button-hidden');
                },

            },
            {
                title: 'Fit Image',
                text: '',
                icon: handleIcons.icon('image-fit'),

                className: 'mw-handle-fit-image-button',

                action: function (el) {
                    el.style.objectFit = 'contain';
                    el.dataset.objectFit = 'contain';
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowFitImageButton(target);

                    selfBtn.classList[selfVisible ? 'remove' : 'add']('mw-le-handle-menu-button-hidden');
                },

            },
            {
                title: 'Fill Image',
                text: '',
                icon: handleIcons.icon('image-fill'),

                className: 'mw-handle-fill-image-button',

                action: function (el) {
                    el.style.objectFit = 'cover';
                    el.dataset.objectFit = 'cover';
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = elementHandleButtonsVisibility.shouldShowFitImageButton(target);;

                    selfBtn.classList[selfVisible ? 'remove' : 'add']('mw-le-handle-menu-button-hidden');
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
                    var selfVisible =   elementHandleButtonsVisibility.shouldShowLinkButton(target);

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
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowUnlinkButton(target);
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


        const elementEditStyleMenu = [
            {
                title: 'Style Editor',
                text: '',
                icon: handleIcons.icon('style-editor'),

                className: 'mw-handle-element-open-style-editor-button',

                action: function (el) {
                    elementActions.openElementStyleEditor(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = elementHandleButtonsVisibility.shouldShowStyleEditorButton(target);
                    selfBtn.style.display = selfVisible ? '' : 'none';
                },

            }

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
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowDragButton(target);
                    selfBtn.style.display = selfVisible ? '' : 'none';
                },

            },

            {
                title: 'Edit',
                text: '',
                icon: handleIcons.icon('edit'),

                className: 'mw-handle-edit-button',

                action: function (el) {

                    elementActions.editElement(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowEditButton(target);


                    selfBtn.style.display = selfVisible ? '' : 'none';
                },

            },

            {
                title: 'Insert module',
                text: '',
                icon: handleIcons.icon('plus'),
                className: 'mw-handle-add-button',

                onTarget: (target, selfBtn) => {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowInsertModuleButton(target);


                    selfBtn.style.display = selfVisible ? '' : 'none';
                },

                action: function (el) {

                    mw.app.editor.dispatch('insertModuleRequest', el);

                }
            },
            ...elementLinkMenu,
            ...elementEditStyleMenu,
            // ...elementLinkMenuGroup,
            {
                title: 'Settings',
                text: '',
                icon: handleIcons.icon('settings'),
                className: 'mw-handle-settings-button',

                action: function (el) {
                    mw.app.editor.dispatch('elementSettingsRequest', el);

                },
                onTarget: function (target, selfBtn) {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowSettingsButton(target);


                    selfBtn.style.display = selfVisible ? '' : 'none';
                }
            },
            {
                title: 'Background color',
                text: '',
                icon: handleIcons.icon('color'),
                className: 'mw-handle-insert-color-button',

                action: function (el, selfBtn) {
                    elementActions.editBackgroundColor(el, selfBtn);

                },
                onTarget: function (target, selfBtn) {
                    var selfVisible =  elementHandleButtonsVisibility.shouldShowEditBackgroundColorButton(target);
                    if(selfVisible) {
                        selfBtn.querySelector('.mw-le--handle-icon--color-color').style.backgroundColor = getComputedStyle(target).backgroundColor;
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
                        elementResetImageSizeMenu

                    },
                ]
            },
        ];


        const tail = [
            {
                title: this.rootScope.lang('Delete'),
                text: '',
                icon: handleIcons.icon('delete'),
                className: 'mw-handle-delete-button',
                action: function (el) {
                    elementActions.deleteElement(el);
                },
                onTarget: function(target, selfBtn) {
                    let selfVisible =  elementHandleButtonsVisibility.shouldShowDeleteElementButton(target);

                    if(selfVisible) {

                        selfBtn.classList.remove('mw-le-handle-menu-button-hidden');
                    } else {
                        selfBtn.classList.add('mw-le-handle-menu-button-hidden');
                    }
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
                },
                {
                    name: 'cloneAbleMenu',
                    nodes: cloneAbleMenu
                },
                {
                    name: 'Image settings',
                    nodes: elementResetImageSizeMenu

                },

                {
                    name: 'tail',
                    nodes: tail
                },
                /*{
                    name: 'tailMenuQuickSettings',
                    nodes: tailMenuQuickSettings
                },*/

            ],

        });
        // Rest of your initMenu code here
    }
}






