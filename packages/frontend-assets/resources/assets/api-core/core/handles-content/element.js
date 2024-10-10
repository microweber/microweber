import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import {HandleIcons} from "../handle-icons";
import {ElementActions} from "./element-actions";
import {DomService} from "../classes/dom";
import {ElementSettingsTarget} from "./element-settings-target";
import {ElementHandleButtonsVisibility} from "./element-handle-buttons-visibility";
import { FreeElementActions } from "./free-element-actions.js";


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
        this.handleIcons = new HandleIcons();
        this.elementActions = new ElementActions(this.rootScope);
        this.elementHandleButtonsVisibility = new ElementHandleButtonsVisibility(this.rootScope);

        this.initMenu();


        this.menu.show();


        this.menusHolder = document.createElement('div');
        this.menusHolder.className = 'mw-handle-item-menus-holder';


        var holder = mw.element(this.menusHolder);

        holder.append(this.menu.root);


        this.root.append(this.menusHolder);


    }

    initMenu() {


        const cloneAbleMenu = [
            {
                title: 'Duplicate',
                text: '',
                icon: this.handleIcons.icon('duplicate'),
                className: 'mw-handle-clone-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowCloneButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },


                action: (el) => {

                    this.elementActions.cloneElement(el);

                }
            },

            {
                title: 'Move backward',
                text: '',
                icon: this.handleIcons.icon('move-backward'),
                className: 'mw-handle-move-back-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowMoveBackwardButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },
                action: (el) => {
                    this.elementActions.moveBackward(el);
                }
            },
            {
                title: 'Move forward',
                text: '',
                icon: this.handleIcons.icon('move-forward'),

                className: 'mw-handle-move-back-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowMoveForwardButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
                action: (el) => {
                    this.elementActions.moveForward(el);
                }
            },
        ];
        const cloneAbleMenuInMoreMenu = [
            {
                title: 'Duplicate',
                text: '',
                icon: this.handleIcons.icon('duplicate'),
                className: 'mw-handle-clone-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowCloneButtonInMoreButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },


                action: (el) => {

                    this.elementActions.cloneElementFirstClonableParent(el);

                }
            },
            {
                title: 'Move backward',
                text: '',
                icon: this.handleIcons.icon('move-backward'),
                className: 'mw-handle-move-back-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowMoveBackwardInMoreButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },
                action: (el) => {
                    this.elementActions.moveBackwardFirstClonableParent(el);
                }
            },
            {
                title: 'Move forward',
                text: '',
                icon: this.handleIcons.icon('move-forward'),

                className: 'mw-handle-move-back-button',
                onTarget: (target, selfBtn) => {

                    const selfVisible = this.elementHandleButtonsVisibility.shouldShowMoveForwardInMoreButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
                action: (el) => {
                    this.elementActions.moveForwardFirstClonableParent(el);
                }
            },

        ];


        const elementResetImageSizeMenu = [

            {
                title: 'Fit Image',
                text: '',
                icon: this.handleIcons.icon('image-fit'),

                className: 'mw-handle-fit-image-button',

                action: (el) => {
                    el.style.objectFit = 'contain';
                    el.dataset.objectFit = 'contain';
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowFitImageButton(target);


                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },
            {
                title: 'Fill Image',
                text: '',
                icon: this.handleIcons.icon('image-fill'),

                className: 'mw-handle-fill-image-button',

                action: (el) => {
                    el.style.objectFit = 'cover';
                    el.dataset.objectFit = 'cover';
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowFitImageButton(target);
                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },
            {
                title: 'Bring to front',
                text: '',
                icon: this.handleIcons.icon('layer-up'),
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
                icon: this.handleIcons.icon('layer-down'),
                className: 'mw-handle-settings-button',

                action: (el) => {
                    FreeElementActions.zIndexDecrement( el);

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = mw.top().app.freeDraggableElementTools.isFreeElement(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                }
            },

        ];

        const elementEditImageUploadMenu = [
            {
                title: 'Change Image',
                text: '',
                icon: this.handleIcons.icon('image-change'),

                className: 'mw-handle-element-open-upload-image-editor-button',

                action: (el) => {

                    this.elementActions.editImage(el);


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowEditImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];


        const elementResetSizeMenu = [
            {
                title: 'Reset Element Size',
                text: '',
                icon: this.handleIcons.icon('reset-image-size'),

                className: 'mw-handle-reset-image-button',

                action: (el) => {
                    this.elementActions.resetElementSizeOnSelfOfParent(el);
                },
                onTarget: (target, selfBtn) => {


                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowResetElementSizeButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },
            ...elementResetImageSizeMenu,
           // ...elementEditImageUploadMenu,


        ];


        const elementLinkMenu = [
            {
                title: 'Link',
                text: '',
                icon: this.handleIcons.icon('link'),

                className: 'mw-handle-element-link-button',

                action: (el) => {
                    this.elementActions.editLink(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowLinkButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },

            {
                title: 'Unlink',
                text: '',
                icon: this.handleIcons.icon('unlink'),

                className: 'mw-handle-element-unlink-button',

                action: (el) => {
                    this.elementActions.removeLink(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowUnlinkButton(target);
                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },

        ];

        const elementLinkMenuGroup = [
            {
                title: 'Link',
                icon: this.handleIcons.icon('link'),
                menu: elementLinkMenu

            },
        ];


        const elementEditStyleMenu = [
            {
                title: 'Style Editor',
                text: '',
                icon: this.handleIcons.icon('style-editor'),

                className: 'mw-handle-element-open-style-editor-button',

                action: (el) => {
                    this.elementActions.openElementStyleEditor(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowStyleEditorButton(target);
                    this.setMenuVisible(selfVisible, selfBtn);

                },

            },
            {
                title: 'Bullet style',
                text: '',
                icon: this.handleIcons.icon('bullet-style'),

                className: 'mw-handle-element-open-style-editor-button',

                action: (el) => {

                    mw.top().app.liveEdit.handles.hide();
                    const types = [
                        'default',
                        'decimal',
                        'circle',
                        'square',
                        'upper-latin',
                        'lower-latin',
                        'upper-roman',
                        'lower-roman',
                        'check',
                        'check-square',
                        'custom-heart',
                        'custom-heart-suit',
                        'custom-x',
                        'custom-four-dot',
                        'custom-flake',
                        'custom-star',
                    ];
                    const classes = types.map(type => `mw__list-style--${type}`)

                    let targetList = false, curr = el;

                    while (curr && curr.parentNode) {
                        targetList = curr.nodeName === 'UL' || curr.nodeName === "OL";
                        if(targetList) {
                            targetList = curr;
                            break;
                        }
                        curr = curr.parentNode;
                    }

                    const id = mw.id('icon-tabs');

                    var css = getComputedStyle(targetList.querySelector('li'), '::marker');

                    let dialogContent = `
                        <nav id="${id}-nav" class="d-flex flex-wrap gap-md-4 gap-3 mb-4">
                            <a class="btn btn-link text-decoration-none mw-admin-action-links js-custom-fields-card-tab">Style</a>
                            <a class="btn btn-link text-decoration-none mw-admin-action-links js-custom-fields-card-tab">Options</a>
                        </nav>

                        <div class="${id}-tab active">
                            <div class="mw-live-edit--bullet-type-selector" style="display: flex;flex-wrap: wrap;font-size: 14px;gap: 10px;justify-content: space-between;">
                                ${types.map(type => `
                                    <div data-bullet-type="${type}" class="mw-live-edit--bullet-type-selector-box">
                                        <ul class="mw__list-style--${type}">
                                            <li>Lorem ipsum dolor</li>
                                            <li>Vestibulum in urna</li>
                                            <li>Sed vel ligula facilisis</li>
                                        </ul>
                                    </div>
                                `)
                                .join('')}
                            </div>
                        </div>
                        <div class="${id}-tab">
                        <div class="form-group">
                              <label class="form-label font-weight-bold my-2">Bullet size</label>
                              <input type="range" min="5" max="100" name="fontSize" class="form-range" value="${parseFloat(css.fontSize)}">
                          </div>

                          <div class="form-group">
                              <label class="form-label font-weight-bold my-2">Bullet color</label>

                              <div id="color-picker-${id}" class="form-control"></div>
                          </div>

                        </div>
                    `;



                    const ok = mw.element(`<button class="btn btn-primary" data-action="save">Update</button>`);
                    const cancel = mw.element(`<button class="btn">Cancel</button>`);

                    const dlg = mw.dialog({
                        content: dialogContent,
                        title: 'Select bullet style',
                        overlay: 'rgba(0,0,0,0)'
                    });

                    dlg.dialogContainer.querySelectorAll('[name="fontSize"]').forEach(node => {
                        node.addEventListener("input", () => {
                            mw.top().app.cssEditor.setPropertyForSelector(`#${targetList.id} > li::marker`, 'font-size', node.value + 'px');
                        })
                    })
                    dlg.dialogContainer.querySelectorAll('.mw-live-edit--bullet-type-selector-box').forEach(node => {
                        node.addEventListener("click", () => {
                            targetList.classList.remove(...classes);
                            targetList.classList.add(`mw__list-style--${node.dataset.bulletType}`);
                            mw.app.registerChange(targetList);
                            mw.app.registerAskUserToStay(true);


                            //dlg.remove();
                        })
                    })


                    mw.tabs({
                        nav: dlg.dialogContainer.querySelectorAll(`#${id}-nav a`),
                        tabs: dlg.dialogContainer.querySelectorAll(`.${id}-tab`),
                    });

                    if(!targetList.id) {
                        targetList.id = mw.id('mw--list')
                    }

                    console.log(css, css.color)

                    var picker = mw.colorPicker({
                        element: `#color-picker-${id}`,
                        method: 'inline',
                        value: css.color,
                        showHEX: true,
                        onchange:function(color){
                            mw.top().app.cssEditor.setPropertyForSelector(`#${targetList.id} > li::marker`, 'color', color);
                        }
                    });




                    ok.on('click', function(){



                        mw.app.registerChange(el)
                        mw.app.registerAskUserToStay(true)


                        dlg.remove();
                    });
                    cancel.on('click', function(){
                        dlg.remove();

                    });
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowBulletStyleButton(target);
                    this.setMenuVisible(selfVisible, selfBtn);

                },

            },

        ];


        const elementEditImageInEditorMenu = [
            {
                title: 'Edit Image',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),

                className: 'mw-handle-element-open-image-editor-fine-tune-button',

                action: (el) => {

                    this.elementActions.editImageWithEditor(el);


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowEditImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            },
            {
                title: ' Image options',
                text: '',
                icon: this.handleIcons.icon('settings'),

                className: 'mw-handle-element-open-image-editor-fine-tune-button',



                action: (el) => {
                    const defaultLoading = ['auto', 'eager'];
                    const dialogContent = `
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Image title" value="${el.title || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alt</label>
                            <input type="text" class="form-control" name="alt" placeholder="Image alt text" value="${el.alt || ''}">
                            <small class="text-muted">This text will appear if image fails to load</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Loading</div>
                            <div>
                                <label class="form-check ">
                                    <input class="form-check-input" type="radio" name="loading" value="eager" ${defaultLoading.includes(el.loading)  ? ' checked ' : ''}>
                                    <span class="form-check-label">Default</span>
                                    <small class="text-muted">Loads an image immediately</small>
                                </label>
                                <label class="form-check ">
                                    <input class="form-check-input" type="radio" name="loading" value="lazy"  ${el.loading === 'lazy' ? ' checked ' : ''}>
                                    <span class="form-check-label">Lazy</span>
                                    <span class="text-muted">Defer loading of until image is present in the viewport</span>
                                </label>
                            </div>
                        </div>
                    `;

                    const ok = mw.element(`<button class="btn btn-primary" data-action="save">Update</button>`);
                    const cancel = mw.element(`<button class="btn">Cancel</button>`);

                    const dlg = mw.dialog({
                        content: dialogContent,'title': 'Image options',
                        footer: [cancel.get(0), ok.get(0)],
                    });




                    ok.on('click', function(){

                        el.title = dlg.dialogContainer.querySelector('[name="title"]').value;
                        el.alt = dlg.dialogContainer.querySelector('[name="alt"]').value;
                        el.loading = dlg.dialogContainer.querySelector('[name="loading"]:checked').value;


                        mw.app.registerChange(el)
                        mw.app.registerAskUserToStay(true)


                        dlg.remove();
                    });
                    cancel.on('click', function(){
                        dlg.remove();

                    });


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowEditImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];


        const elementEditImageAlignMenuAlignLeft = [
            {
                title: 'Align Image Left',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),

                className: 'mw-handle-element-open-image-editor-align-image-left-button',

                action: (el) => {

                    this.elementActions.alignImage(el, 'left');

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowAlignImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];

        const elementEditImageAlignMenuAlignRight = [
            {
                title: 'Align Image Right',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),

                className: 'mw-handle-element-open-image-editor-align-image-right-button',

                action: (el) => {

                    this.elementActions.alignImage(el, 'right');

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowAlignImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];

        const elementEditImageAlignMenuAlignCenter = [
            {
                title: 'Align Image Center',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),

                className: 'mw-handle-element-open-image-editor-align-image-right-button',

                action: (el) => {

                    this.elementActions.alignImage(el, 'center');

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowAlignImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];
        const elementEditImageAlignMenuAlignClear = [
            {
                title: 'Align Image Clear',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),

                className: 'mw-handle-element-open-image-editor-align-image-clear-button',

                action: (el) => {

                    this.elementActions.alignImage(el, 'clear');

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowAlignImageButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];


        const elementEditImageAlignMenu = [
            {
                title: 'Align Image',
                text: '',
                icon: this.handleIcons.icon('fine-tune'),
                className: 'mw-handle-element-open-image-editor-align-image-button',
                menu: [
                    ...elementEditImageAlignMenuAlignLeft,
                    ...elementEditImageAlignMenuAlignCenter,
                    ...elementEditImageAlignMenuAlignRight,
                    ...elementEditImageAlignMenuAlignClear,
                ]
            }
        ];


        const elementBackgroundImageMenu = [
            {
                title: 'Background Image',
                text: '',
                icon: this.handleIcons.icon('background-image'),

                className: 'mw-handle-element-open-background-image-editor-button',

                action: (el) => {

                    this.elementActions.editBackgroundImage(el);


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowBackroundImageEditorButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];
        const elementBackgroundImageMenuOnMoreButton = [
            {
                title: 'Background Image',
                text: '',
                icon: this.handleIcons.icon('background-image'),

                className: 'mw-handle-element-open-background-image-editor-button',

                action: (el) => {

                    this.elementActions.editBackgroundImageOnParent(el);


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowBackroundImageEditorButtonOnTheMoreButton(target);
                    this.setMenuVisible(selfVisible, selfBtn);
                },
            }
        ];

        const elementMakeFreeOnMoreButton = [
            {
                title: 'Make Free Element',
                text: '',
                icon: this.handleIcons.icon('free-element'),

                className: 'mw-handle-element-make-free-button',

                action: (el) => {

                    this.elementActions.makeFreeDraggableElement(el);


                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowFreeDraggableButton(target);

                    this.setMenuVisible(false, selfBtn);
                },
            }
        ];

        const primaryMenu = [
            {
                title: 'Drag',
                text: '',
                icon: this.handleIcons.icon('drag'),
                className: 'mw-handle-drag-button mw-handle-drag-button-element',
                action: () => {
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowDragButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },

            },

            {
                title: 'Edit',
                text: '',
                icon: this.handleIcons.icon('edit'),

                className: 'mw-handle-edit-button',

                action: (el) => {

                    this.elementActions.editElement(el);
                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowEditButton(target);


                    this.setMenuVisible(selfVisible, selfBtn);
                },

            },

            {
                title: 'Insert module',
                text: '',
                icon: this.handleIcons.icon('plus'),
                className: 'mw-handle-add-button',

                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowInsertModuleButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                },

                action: (el) => {

                    mw.app.editor.dispatch('insertModuleRequest', el);

                }
            },
            ...elementLinkMenu,
            ...elementEditStyleMenu,

            ...elementBackgroundImageMenu,



            {
                title: 'Settings',
                text: '',
                icon: this.handleIcons.icon('settings'),
                className: 'mw-handle-settings-button',

                action: (el) => {
                    mw.app.editor.dispatch('elementSettingsRequest', el);

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowSettingsButton(target);

                    this.setMenuVisible(selfVisible, selfBtn);

                }
            },
            {
                title: 'Background color',
                text: '',
                icon: this.handleIcons.icon('color'),
                className: 'mw-handle-insert-color-button',

                action: (el, selfBtn) => {
                    this.elementActions.editBackgroundColor(el, selfBtn);

                },
                onTarget: (target, selfBtn) => {
                    var selfVisible = this.elementHandleButtonsVisibility.shouldShowEditBackgroundColorButton(target);
                    if (selfVisible) {
                        selfBtn.querySelector('.mw-le--handle-icon--color-color').style.backgroundColor = getComputedStyle(target).backgroundColor;
                    }

                    this.setMenuVisible(selfVisible, selfBtn);
                }
            },

            //    ...cloneAbleMenuInMoreMenu,

            ...elementEditImageUploadMenu,
           // ...elementEditImageInEditorMenu,
            //   ...elementEditImageAlignMenu,
            // ...elementEditImageAlignMenuAlignLeft,
            // ...elementEditImageAlignMenuAlignRight,
            // ...elementEditImageAlignMenuAlignCenter,
            // ...elementEditImageAlignMenuAlignClear,
            ...elementMakeFreeOnMoreButton,


        ];

        var tailMenuQuickSettings = [];


        var shouldShowMoreMenu = true;

        // var shouldShowMoreMenu =
        //       cloneAbleMenuInMoreMenu.length > 0 ||
        //       elementResetSizeMenu.length > 0 ||
        //       elementEditImageUploadMenu.length > 0 ||
        //       elementEditImageInEditorMenu.length > 0 ||
        //      // elementMakeFreeOnMoreButton.length > 0 ||
        //       elementBackgroundImageMenuOnMoreButton.length > 0;
        //


        if (shouldShowMoreMenu) {


            tailMenuQuickSettings = [
                {
                    title: 'Quick Settings',
                    icon: this.handleIcons.icon('more'),
                    type: 'list',
                    menu: [
                        {
                            name: 'More settings menu',
                            nodes:
                                [
                                    ...elementEditImageUploadMenu,
                                    ...elementEditImageInEditorMenu,
                                    ...cloneAbleMenuInMoreMenu,
                              //      ...elementMakeFreeOnMoreButton,
                                ]

                        },
                        {
                            name: 'Reset Element Size',
                            nodes:
                            elementResetSizeMenu,
                        },
                        {
                            name: 'Image Background',
                            nodes:
                            elementBackgroundImageMenuOnMoreButton

                        }
                    ]
                },
            ];
        }


        const tail = [
            {
                title: this.rootScope.lang('Delete'),
                text: '',
                icon: this.handleIcons.icon('delete'),
                className: 'mw-handle-delete-button',
                action: (el) => {
                    this.elementActions.deleteElement(el);
                },
                onTarget: (target, selfBtn) => {
                    let selfVisible = this.elementHandleButtonsVisibility.shouldShowDeleteElementButton(target);

                    if (selfVisible) {

                        selfBtn.classList.remove('mw-le-handle-menu-button-hidden');
                    } else {
                        selfBtn.classList.add('mw-le-handle-menu-button-hidden');
                    }
                }
            }
        ]


        var menuItems = [
            {
                name: 'primary',
                nodes: primaryMenu
            },
            {
                name: 'dynamic',
                nodes: []
            },

        ];

        if (tailMenuQuickSettings.length > 0) {


            menuItems.push({
                name: 'tailMenuQuickSettings',
                nodes: tailMenuQuickSettings
            });
        }

        menuItems.push({
            name: 'tail',
            nodes: tail
        });

        this.menu = new HandleMenu({
            id: 'mw-handle-item-element-menu',
            title: 'Element',
            handleScope: this,
            menus: menuItems,


        });


    }


    setMenuVisible(isVisible, node) {
        if (isVisible) {
            node.classList.remove('mw-le-handle-menu-button-hidden');
        } else {
            node.classList.add('mw-le-handle-menu-button-hidden');
        }

    }
}






