<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>


    <link rel="stylesheet" href="<?php print site_url() ?>userfiles/modules/microweber/css/ui.css">

    <script  type="module"  src="<?php print site_url('userfiles/modules/microweber/api/live-edit-app/index.js'); ?>"></script>



    <link rel="stylesheet" href="<?php print site_url('userfiles/modules/microweber/api/live-edit-app/css/gui.css'); ?>">
    <link rel="stylesheet" href="<?php print site_url('userfiles/modules/microweber/api/live-edit-app/css/index.css'); ?>">




    @livewireScripts

    @livewireStyles


    <script>
        mw.require('editor.js');
        mw.require('css_parser.js');
        mw.require('le2/modules-list.js');

    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>


        $.fn.reload_module = function () {

        }


        var frame, frameHolder;






        var _modulesCache, _layotsCache;

        var _modulesDataLoader = function (modulesDialog) {
            var modulesList = new ModulesList({
                data: _modulesCache
            });
            modulesList.createCategorized().then(function () {
                modulesDialog.append(modulesList.root)

            })
        }

        var _layoutsDataLoader = function (cmmodulesDialog) {
            var modulesList = new ModulesList({
                data: _layotsCache
            });


            modulesList.create().then(function () {
                var grid = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-row'
                    }
                });
                var colSidebar = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-col'
                    }
                });
                var colContent = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-col'
                    }
                });
                grid.append(colSidebar);
                grid.append(colContent);
                mw.element(modulesList.root).append(grid);
                colSidebar.append(modulesList.searchBlock);

                var categoriesTitle = mw.element({
                    props: {
                        innerHTML: 'Categories',
                        className: 'mw-le-layouts-dialog-categories-title'
                    }
                });
                colSidebar.append(categoriesTitle);
                colSidebar.append(modulesList.categoriesNavigation);
                colContent.append(modulesList.modulesList);

                cmmodulesDialog.append(modulesList.root);


            })
        }

        window.command = {
            cssEditor: function () {
                document.getElementById('css-editor-template').classList.toggle('active')

            },
            insertModule: function (target) {
                var cmmodulesDialog = new CommandDialog('mw-le-modules-dialog');
                var modulesDialog = cmmodulesDialog.dialog;


                mw.$('#mw-plus-tooltip-selector li').each(function () {
                    this.onclick = function () {
                        var name = mw.$(this).attr('data-module-name');
                        var conf = {class: this.className};
                        if (name === 'layout') {
                            conf.template = mw.$(this).attr('template');
                        }

                        mw.module.insert(mw._activeElementOver, name, conf, mw.handleElement.positionedAt, mw.liveEditState);
                        mw.wysiwyg.change(mw._activeElementOver)
                        tooltip.remove();
                    };
                });


                if (_modulesCache) {
                    _modulesDataLoader(modulesDialog)
                } else {
                    mw.spinner({
                        element: modulesDialog.get(0),
                        decorate: true
                    });
                    /* demo */
                    fetch('<?php print route('api.module.list'); ?>?layout_type=layout')
                        .then(function (data) {
                            return data.json();
                        }).then(function (data) {
                        _modulesCache = data;
                        _modulesDataLoader(modulesDialog)
                        mw.spinner({
                            element: modulesDialog.get(0),
                            decorate: true
                        }).remove()
                    })
                }


            },
            insertLayout: function () {


                var cmmodulesDialog = new CommandDialog('mw-le-layouts-dialog')
                var layOutsDialog = cmmodulesDialog.dialog;


                if (_layotsCache) {
                    _layoutsDataLoader(layOutsDialog);
                    return;
                }

                mw.spinner({
                    element: layOutsDialog.get(0),
                    decorate: true
                })


                fetch('<?php print route('api.module.list'); ?>?layout_type=layout&elements_mode=true&group_layouts_by_category=true')
                    .then(function (data) {
                        return data.json();
                    }).then(function (data) {
                    _layotsCache = data;
                    _layoutsDataLoader(layOutsDialog)
                    mw.spinner({
                        element: layOutsDialog.get(0),
                        decorate: true
                    }).remove()
                })
            }
        }


        addEventListener('load', () => {

            mw.element('#bubble-nav [data-command]').on('click', function () {
                if (command[this.dataset.command]) {
                    command[this.dataset.command]();
                }
            })

            var userMenuWrapper = document.getElementById('user-menu-wrapper');
            document.getElementById('toolbar-user-menu-button').addEventListener('click', function () {
                userMenuWrapper.classList.toggle('active')
            });


            var initEditor = function () {
                var holder = document.querySelector('#mw-live-edit-editor');

                var _fontFamilyProvider = function () {
                    var _e = {};
                    this.on = function (e, f) {
                        _e[e] ? _e[e].push(f) : (_e[e] = [f])
                    };
                    this.dispatch = function (e, f) {
                        _e[e] ? _e[e].forEach(function (c) {
                            c.call(this, f);
                        }) : '';
                    };

                    this.provide = function (fontsArray) {
                        this.dispatch('change', fontsArray.map(function (font) {
                            return {
                                label: font,
                                value: font,
                            }
                        }))
                    }

                };

                var fontFamilyProvider = new _fontFamilyProvider();
                window.fontFamilyProvider = fontFamilyProvider;
                frame.contentWindow.fontFamilyProvider = fontFamilyProvider;


                window.liveEditor = mw.Editor({
                    document: frame.contentWindow.document,
                    executionDocument: frame.contentWindow.document,
                    actionWindow: frame.contentWindow,
                    element: holder,
                    mode: 'document',
                    regions: '.edit',
                    skin: 'le2',
                    editMode: 'liveedit',
                    scopeColor: 'white',
                    controls: [
                        [

                            {
                                group: {
                                    icon: 'mdi mdi-format-title',
                                    controls: ['format', 'lineHeight']
                                }
                            },

                            {
                                group: {
                                    controller: 'bold',
                                    controls: ['italic', 'underline', 'strikeThrough', 'removeFormat']
                                }
                            },
                            'fontSelector',

                            'fontSize',


                            {
                                group: {
                                    controller: 'alignLeft',
                                    controls: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']
                                }
                            },

                            {
                                group: {
                                    controller: 'ul',
                                    controls: ['ol']
                                }
                            },


                            'image',
                            {
                                group: {
                                    controller: 'link',
                                    controls: ['unlink']
                                }
                            },
                            {
                                group: {
                                    controller: 'textColor',
                                    controls: ['textBackgroundColor']
                                }
                            },


                        ]
                    ],
                    smallEditorPositionX: 'center',
                    smallEditorSkin: 'lite',

                    interactionControls: [],

                    id: 'live-edit-wysiwyg-editor',

                    minHeight: 250,
                    maxHeight: '70vh',
                    state: mw.liveEditState,

                    fontFamilyProvider: fontFamilyProvider
                });



                var btnUndo = document.getElementById('toolbar-undo')
                var btnRedo = document.getElementById('toolbar-redo')

                liveEditor.state.on('record', function () {

                    btnRedo.disabled = !liveEditor.state.hasPrev;
                    btnUndo.disabled = !liveEditor.state.hasNext;
                })
                liveEditor.state.on('change', function () {

                    btnRedo.disabled = !liveEditor.state.hasPrev;
                    btnUndo.disabled = !liveEditor.state.hasNext;
                })

                btnUndo.addEventListener('click', function () {
                    liveEditor.state.undo()
                });
                btnRedo.addEventListener('click', function () {
                    liveEditor.state.redo()
                });

                /*                liveEditor.on('action', function (){
                                    mw.wysiwyg.change(liveEditor.api.elementNode(liveEditor.api.getSelection().focusNode))
                                })
                                liveEditor.on('smallEditorReady', function (){
                                    fontFamilyProvider.provide(mw.top().wysiwyg.fontFamiliesExtended);
                                })
                                $(liveEditor).on('selectionchange', function (){
                                    var sel = liveEditor.getSelection();
                                    if(sel.rangeCount) {
                                        liveEditor.lastRange =  sel.getRangeAt(0) ;
                                    } else {
                                        liveEditor.lastRange = undefined;
                                    }

                                })*/

                holder.innerHTML = '';
                holder.appendChild(liveEditor.wrapper);


                var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
                if (typeof memPin === 'undefined' && typeof liveEditor.smallEditorApi !== 'undefined') {
                    liveEditor.smallEditorApi.pin()
                }
                mw.liveEditApi.add('richTextEditor', liveEditor);

                mw.liveEditApi.set('richTextEditorAPI', liveEditor.api);
            }


            initEditor()


            var _reTypes = {
                tablet: 800,
                phone: 400,
                desktop: '100%',
            }
            const responsiveEmulatorSet = function (key) {
                var width = _reTypes[key];
                if (typeof width === 'number') {
                    width = width + 'px'
                }
                frame.style.width = width;

                mw.element('[data-preview]').removeClass('active')
                mw.element('[data-preview="' + key + '"]').addClass('active')
            };


            Array.from(document.querySelectorAll('#preview-nav [data-preview]')).forEach(node => {
                node.addEventListener('click', function () {
                    responsiveEmulatorSet(this.dataset.preview)
                })
            });

            document.getElementById('save-button').addEventListener('click', function () {
                frame.contentWindow.mw.drag.save()
            })


        });


        var _hascss, isPreview = true;


        var previewMode = function () {
            document.documentElement.classList.add('preview');
            document.documentElement.style.setProperty('--toolbar-height', '0px');
            frame.contentWindow.document.documentElement.classList.add('mw-le--page-preview');
            frame.contentWindow.document.body.classList.remove('mw-live-edit');
            document.querySelector('#css-editor-template').classList.remove('active');
            document.querySelector('#bubble-nav').classList.remove('active');
            document.querySelector('#user-menu-wrapper').classList.remove('active');
        }

        var liveEditMode = function () {
            document.documentElement.classList.remove('preview');
            document.documentElement.style.setProperty('--toolbar-height', document.documentElement.style.getPropertyValue('--toolbar-static-height'));
            frame.contentWindow.document.documentElement.classList.remove('mw-le--page-preview');
            frame.contentWindow.document.body.classList.add('mw-live-edit');
            document.querySelector('#bubble-nav').classList.add('active');
        }

        var pagePreviewToggle = function () {
            isPreview = !isPreview;
            if (!isPreview) {
                previewMode();
            } else {
                liveEditMode()
            }


            if (!_hascss) {
                _hascss = true;
                var css = `
                html.mw-le--page-preview body{
                    padding-top: 0 !important
                }
                html.mw-le--page-preview .mw_image_resizer,
                html.mw-le--page-preview #live_edit_toolbar_holder,
                html.mw-le--page-preview .mw-handle-item,
                html.mw-le--page-preview .mw-selector,
                html.mw-le--page-preview .mw_dropable,
                html.mw-le--page-preview .mw-padding-ctrl,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-cloneable-control,
                html.mw-le--page-preview #live_edit_toolbar_holder
                {
                    display: none !important
                }
            `

                var node = frame.contentWindow.document.createElement('style');
                node.textContent = css;
                frame.contentWindow.document.body.appendChild(node)
            }


        }
    </script>


    <?php print event_trigger('mw.live_edit.head'); ?>

</head>
<body>


<div id="bubble-nav" class="active">
    <span title="Insert layout" data-command="insertLayout">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path
                d="M13 15.6C13.3 15.8 13.7 15.8 14 15.6L19 12.7V13C19.7 13 20.4 13.1 21 13.4V11.6L22 11C22.5 10.7 22.6 10.1 22.4 9.6L20.9 7.1C20.8 6.9 20.7 6.7 20.5 6.6L12.6 2.2C12.4 2.1 12.2 2 12 2S11.6 2.1 11.4 2.2L3.6 6.6C3.4 6.7 3.2 6.8 3.1 7L1.6 9.6C1.3 10.1 1.5 10.7 2 11C2.3 11.2 2.7 11.2 3 11V16.5C3 16.9 3.2 17.2 3.5 17.4L11.4 21.8C11.6 21.9 11.8 22 12 22S12.4 21.9 12.6 21.8L13.5 21.3C13.2 20.7 13.1 20 13 19.3M11 19.3L5 15.9V9.2L11 12.6V19.3M20.1 9.7L13.8 13.3L13.2 12.3L19.5 8.7L20.1 9.7M12 10.8V4.2L18 7.5L12 10.8M20 15V18H23V20H20V23H18V20H15V18H18V15H20Z"/></svg>
    </span>
    <span title="Insert module" data-command="insertModule"></span>
    <span title="Edit styles" data-command="cssEditor"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                            viewBox="0 0 24 24"><title>brush-outline</title><path
                d="M7 16C7.55 16 8 16.45 8 17C8 18.1 7.1 19 6 19C5.83 19 5.67 19 5.5 18.95C5.81 18.4 6 17.74 6 17C6 16.45 6.45 16 7 16M18.67 3C18.41 3 18.16 3.1 17.96 3.29L9 12.25L11.75 15L20.71 6.04C21.1 5.65 21.1 5 20.71 4.63L19.37 3.29C19.17 3.09 18.92 3 18.67 3M7 14C5.34 14 4 15.34 4 17C4 18.31 2.84 19 2 19C2.92 20.22 4.5 21 6 21C8.21 21 10 19.21 10 17C10 15.34 8.66 14 7 14Z"/></svg></span>

    <span></span>
</div>


<?php
$back_url = site_url() . 'admin/view:content';
if (defined('CONTENT_ID')) {
    if ((!defined('POST_ID') or POST_ID == false) and !defined('PAGE_ID') or PAGE_ID != false and PAGE_ID == CONTENT_ID) {
        $back_url .= '#action=showposts:' . PAGE_ID;
    } else {
        $back_url .= '#action=editpage:' . CONTENT_ID;
    }
} else if (isset($_COOKIE['back_to_admin'])) {
    $back_url = $_COOKIE['back_to_admin'];
}

$user = get_user();

?>




<div id="toolbar">
    <div class="toolbar-nav toolbar-nav-hover">
        <a href="<?php print $back_url; ?>" class="mw-le-btn mw-le-btn-icon mw-le-btn-primary2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 32px;">
                <path d="M21,11H6.83L10.41,7.41L9,6L3,12L9,18L10.41,16.58L6.83,13H21V11Z"/>
            </svg>
        </a>
    </div>
    <nav id="preview-nav" class="toolbar-nav toolbar-nav-hover">

            <span data-preview="desktop" class="active">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 36.17" style="enable-background:new 0 0 48 36.17;" xml:space="preserve">


                    <path d="M25.59,34.11h-3.58v-6.59h3.58V34.11z M14.16,34.88L14.16,34.88c0-0.71,0.58-1.29,1.29-1.29h17.1
                        c0.71,0,1.29,0.58,1.29,1.29v0c0,0.71-0.58,1.29-1.29,1.29h-17.1C14.74,36.17,14.16,35.59,14.16,34.88z"/>


                    <path class="st0" d="M3.32,27.6h41.35c1.53,0,2.76-1.24,2.76-2.76V3.24c0-1.53-1.24-2.76-2.76-2.76H3.32
                        c-1.53,0-2.76,1.24-2.76,2.76v21.6C0.56,26.37,1.8,27.6,3.32,27.6z"/>

                </svg>
            </span>

        <span data-preview="tablet">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">

                <path class="st0" d="M24.45,39.5c0.56,0,1.03-0.18,1.39-0.55c0.37-0.37,0.55-0.83,0.55-1.39s-0.18-1.03-0.55-1.39
                    c-0.37-0.37-0.83-0.55-1.39-0.55s-1.03,0.18-1.39,0.55c-0.37,0.37-0.55,0.83-0.55,1.39s0.18,1.03,0.55,1.39
                    C23.42,39.32,23.88,39.5,24.45,39.5z M7,46c-0.8,0-1.5-0.3-2.1-0.9C4.3,44.5,4,43.8,4,43V5c0-0.8,0.3-1.5,0.9-2.1C5.5,2.3,6.2,2,7,2
                    h34c0.8,0,1.5,0.3,2.1,0.9C43.7,3.5,44,4.2,44,5v38c0,0.8-0.3,1.5-0.9,2.1C42.5,45.7,41.8,46,41,46H7z"/>
                </svg>

            </span>

        <span data-preview="phone">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">

                <path class="st0" d="M18,11.5c0.43,0,0.79-0.14,1.08-0.43c0.28-0.28,0.42-0.64,0.42-1.07s-0.14-0.79-0.42-1.07
                    C18.79,8.64,18.43,8.5,18,8.5s-0.79,0.14-1.08,0.43C16.64,9.21,16.5,9.57,16.5,10s0.14,0.79,0.42,1.07
                    C17.21,11.36,17.57,11.5,18,11.5z M13,46c-0.8,0-1.5-0.3-2.1-0.9C10.3,44.5,10,43.8,10,43V5c0-0.8,0.3-1.5,0.9-2.1
                    C11.5,2.3,12.2,2,13,2h22c0.8,0,1.5,0.3,2.1,0.9C37.7,3.5,38,4.2,38,5v38c0,0.8-0.3,1.5-0.9,2.1C36.5,45.7,35.8,46,35,46H13z"/>
                </svg>
            </span>
    </nav>
    <div class="toolbar-nav" id="mw-live-edit-editor"></div>
    <div class="toolbar-col">
        <div class="toolbar-nav toolbar-nav-hover">
            <button class="mw-le-btn mw-le-btn-icon" id="toolbar-undo" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"/>
                </svg>
            </button>
            <button class="mw-le-btn mw-le-btn-icon" id="toolbar-redo" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>redo</title>
                    <path
                        d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z"/>
                </svg>
            </button>
            <span class="mw-le-btn mw-le-btn-primary" id="save-button">
                    Save
                </span>
        </div>
        <span style="width: 50px"></span>
        <span class="mw-le-btn mw-le-btn-icon" id="preview-button" onclick="pagePreviewToggle()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>eye</title><path
                            d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/></svg>
                </span>
        <div id="user-menu-wrapper">
                <span class="mw-le-hamburger" id="toolbar-user-menu-button">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            <div id="user-menu" class="mw-le-nav-box">
                <div class="mw-le-nav-box-content" id="user-menu-header">
                    <small>Project</small>
                    <h3>Boris Website</h3>
                    <span class="mw-le-btn mw-le-btn-sm ">
                            In Test Period
                        </span>
                    <span class="mw-le-btn mw-le-btn-sm mw-le-btn-primary2">
                            Upgrade
                        </span>
                </div>
                <nav>
                    <a href="<?php print $back_url?>">
                        <svg viewBox="0 0 40 40">
                            <path
                                d="M20 27.3l2.1-2.1-3.7-3.7h9.1v-3h-9.1l3.7-3.7-2.1-2.1-7.3 7.3 7.3 7.3zM20 40c-2.73 0-5.32-.52-7.75-1.58-2.43-1.05-4.56-2.48-6.38-4.3s-3.25-3.94-4.3-6.38S0 22.73 0 20c0-2.77.53-5.37 1.57-7.8s2.48-4.55 4.3-6.35 3.94-3.22 6.38-4.28S17.27 0 20 0c2.77 0 5.37.53 7.8 1.57s4.55 2.48 6.35 4.28c1.8 1.8 3.23 3.92 4.28 6.35C39.48 14.63 40 17.23 40 20c0 2.73-.52 5.32-1.58 7.75-1.05 2.43-2.48 4.56-4.28 6.38-1.8 1.82-3.92 3.25-6.35 4.3C25.37 39.48 22.77 40 20 40zm0-3c4.73 0 8.75-1.66 12.05-4.97C35.35 28.71 37 24.7 37 20c0-4.73-1.65-8.75-4.95-12.05C28.75 4.65 24.73 3 20 3c-4.7 0-8.71 1.65-12.02 4.95S3 15.27 3 20c0 4.7 1.66 8.71 4.98 12.03C11.29 35.34 15.3 37 20 37z"/>
                        </svg>
                        Back to Admin
                    </a>
                    <a href="">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 40 40" xml:space="preserve"
                             enable-background="new 0 0 40 40"><path
                                d="M14.7 23c-2 0-3.6-.7-5-2-1.3-1.4-2-3-2-4.9 0-1.9.7-3.5 2-4.9 1.4-1.3 3-2 5-2 1.8 0 3.5.7 4.8 2 1.4 1.4 2 3 2 4.9 0 1.9-.6 3.5-2 4.9-1.3 1.3-3 2-4.8 2zm0-3a3.8 3.8 0 0 0 3.9-3.9c0-1.1-.4-2-1.2-2.8a3.8 3.8 0 0 0-2.7-1c-1.1 0-2 .3-2.8 1-.8.8-1.1 1.7-1.1 2.8 0 1 .3 2 1.1 2.8.8.7 1.7 1.1 2.8 1.1zm15 5.3c-1.5 0-2.7-.5-3.8-1.6-1-1-1.5-2.2-1.5-3.7s.5-2.7 1.6-3.8 2.2-1.5 3.7-1.5 2.7.5 3.8 1.6S35 18.4 35 20s-.5 2.7-1.6 3.8-2.2 1.5-3.7 1.5zM17.1 36.8c1.6-3 3.6-5 6.1-6S28 29 29.7 29a12.6 12.6 0 0 1 4.2.6A18.3 18.3 0 0 0 37 20c0-4.7-1.6-8.8-5-12-3.3-3.3-7.3-5-12-5S11.2 4.7 8 8a16.8 16.8 0 0 0-2.2 21.2 19.2 19.2 0 0 1 13.8-1.4 13.6 13.6 0 0 0-3.2 2.2H14.8a16.2 16.2 0 0 0-7.1 1.6c1.2 1.4 2.7 2.5 4.3 3.4s3.4 1.5 5.2 1.8zM20 40A20.3 20.3 0 0 1 1.6 27.7 19.4 19.4 0 0 1 5.9 5.8a20.2 20.2 0 0 1 21.9-4.2A20.3 20.3 0 0 1 40 20a20.3 20.3 0 0 1-12.2 18.4c-2.4 1-5 1.6-7.8 1.6z"/></svg>
                        Users
                    </a>
                    <a href="">
                        <svg viewBox="0 0 40 40">
                            <path
                                d="M15.4 40l-1-6.3c-.63-.23-1.3-.55-2-.95-.7-.4-1.32-.82-1.85-1.25l-5.9 2.7L0 26l5.4-3.95a5.1 5.1 0 01-.12-1.02c-.02-.39-.03-.73-.03-1.03s.01-.64.02-1.02c.02-.38.06-.73.12-1.02L0 14l4.65-8.2 5.9 2.7c.53-.43 1.15-.85 1.85-1.25.7-.4 1.37-.7 2-.9l1-6.35h9.2l1 6.3c.63.23 1.31.54 2.02.93.72.38 1.33.81 1.83 1.27l5.9-2.7L40 14l-5.4 3.85c.07.33.11.69.12 1.08a19.5 19.5 0 010 2.13c-.02.37-.06.72-.12 1.05L40 26l-4.65 8.2-5.9-2.7c-.53.43-1.14.86-1.83 1.28-.68.42-1.36.72-2.02.92l-1 6.3h-9.2zM20 26.5c1.8 0 3.33-.63 4.6-1.9s1.9-2.8 1.9-4.6-.63-3.33-1.9-4.6-2.8-1.9-4.6-1.9-3.33.63-4.6 1.9-1.9 2.8-1.9 4.6.63 3.33 1.9 4.6 2.8 1.9 4.6 1.9zm0-3c-.97 0-1.79-.34-2.48-1.02-.68-.68-1.02-1.51-1.02-2.48s.34-1.79 1.02-2.48c.68-.68 1.51-1.02 2.48-1.02s1.79.34 2.48 1.02c.68.68 1.02 1.51 1.02 2.48s-.34 1.79-1.02 2.48c-.69.68-1.51 1.02-2.48 1.02zM17.8 37h4.4l.7-5.6c1.1-.27 2.14-.68 3.12-1.25s1.88-1.25 2.68-2.05l5.3 2.3 2-3.6-4.7-3.45c.13-.57.24-1.12.33-1.67s.12-1.11.12-1.67-.03-1.12-.1-1.67-.18-1.11-.35-1.67L36 13.2l-2-3.6-5.3 2.3c-.77-.87-1.63-1.59-2.6-2.17s-2.03-.96-3.2-1.12L22.2 3h-4.4l-.7 5.6c-1.13.23-2.19.63-3.17 1.2s-1.86 1.27-2.62 2.1L6 9.6l-2 3.6 4.7 3.45c-.13.57-.24 1.12-.32 1.67s-.13 1.11-.13 1.68.04 1.12.12 1.67c.08.55.19 1.11.32 1.67L4 26.8l2 3.6 5.3-2.3c.8.8 1.69 1.48 2.68 2.05s2.02.98 3.12 1.25l.7 5.6z"/>
                        </svg>
                        Website Settings
                    </a>
                    <a href="">
                        <svg viewBox="0 0 40 32.29">
                            <path
                                d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/>
                        </svg>
                        Plans and Payments
                    </a>
                    <a href="<?php print site_url('logout'); ?>">
                        <svg viewBox="0 0 36 36.1">
                            <path
                                d="M3 36.1c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V22.6h3v10.5h30V3H3v10.6H0V3C0 2.2.3 1.5.9.9S2.2 0 3 0h30c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1v30.1c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3zm11.65-8.35L12.4 25.5l5.9-5.9H0v-3h18.3l-5.9-5.9 2.25-2.25 9.65 9.65-9.65 9.65z"/>
                        </svg>
                        Log out
                    </a>
                </nav>
            </div>
        </div>
    </div>


</div>


<div id="css-editor-template">
    <?php include mw_includes_path() . 'toolbar' . DS . 'editor_tools' . DS . 'rte_css_editor' . DS . 'index.php'; ?>
</div>
<div id="root"></div>
<div id="live-edit-frame-holder">
    <iframe id="live-editor-frame"
            title="Inline Frame Example"
            width="100%"
            height="2000"
            referrerpolicy="no-referrer"
            frameborder="0"
            src="<?php print site_url(); ?>?editmode=n&_rand=<?php print rand(0, 99999) ?>"
            data-src="about:blank">
    </iframe>
</div>


<script>

    class MWServiceProvider {
        #services = {};

        #reserved = ['add', 'get', 'set'];

        get(name) {
            return this.#services[name];
        }

        #_setService(name, service) {
            if(this.#reserved.indexOf(name) === -1) {
                this.#services[name] = service;
                this[name] = service;
                if(typeof service.onAdd === 'function') {
                    service.onAdd()
                }
            }
        }
        add(name, service) {
            if(!!name && !!service && !this.get(name)) {
                this.#_setService(name, service)
            }
        }
        set(name, service) {
            if(!!name && !!service) {
                this.#_setService(name, service)
            }
        }
        register(name, service){
            return this.set(name, service);
        }
    }

    class MWClassContainer {
        #modules = [];

        #isAsync(func) {
            return typeof func === 'function' && func.constructor.name === 'AsyncFunction';
        }
        #run(method, instance, data) {
            return new Promise(async resolve => {
                if(!method || !instance || !instance[method])  {
                    resolve(null)
                }
                method = instance[method];
                let res;
                if (this.#isAsync(method)) {
                    res = await method(data);
                } else {
                    res = method(data);
                }
                resolve(res);
            });
        }

        call(method, data){
            let i = 0, l = this.#modules.length;
            for( ; i < l; i++) {
                if (this.#modules[i][method]) {
                    this.#run(method, this.#modules[i], data);
                }
            }
        }
        getInstanceByName(name){
            let i = 0, l = this.#modules.length;
            for( ; i < l; i++) {
                if (this.#modules[i].name === name || this.#modules[i].constructor.name === name) {
                    return this.#modules[i];
                }
            }
        }

        getModules() {
            return this.#modules;
        }

        async register(classRef) {
            const instance = new classRef();
            this.#modules.push(instance);
            await this.#run('onRegister', instance);
        }

        async remove(instance) {
            if(typeof instance === 'string') {
                instance = this.getInstanceByName(instance);
            }
            if(!instance) {
                return;
            }
            const index = this.#modules.indexOf(instance);
            if (index === -1) {
                return;
            }
            this.#modules.splice(index, 1);
            await this.#run('onDestroy', instance);
        }
    }

    frame = document.getElementById('live-editor-frame');
    frameHolder = document.getElementById('live-edit-frame-holder');
    mw.spinner({
        element: frameHolder,
        size: 52,
        decorate: true
    })



</script>

<script>
    mw.quickSettings = {}
</script>

<script>



    mw.liveEditApp = new MWClassContainer();
    mw.liveEditApi = new MWServiceProvider();


    setTimeout(function (){
         mw.liveEditApp.remove('HyperExtender');
    }, 2222)


    frame.addEventListener('load', function () {
        var doc = frame.contentWindow.document;
        var link = doc.createElement('link');
        link.rel = 'stylesheet';
        link.href = '<?php print site_url('userfiles/modules/microweber/api/liveedit2/css/dist.css'); ?>';
        doc.head.prepend(link);

        liveEdit = new LiveEdit({
            root: frame.contentWindow.document.body,
            strict: false,
            mode: 'auto',
            document: frame.contentWindow.document
        })


        mw.liveEditApp.call('onLiveEditReady')

        mw.liveEditApi.add('liveEdit', liveEdit);
        mw.liveEditApi.add('state', mw.liveEditState);





        mw.spinner({
            element: frameHolder
        }).remove();





    });



</script>


<?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>


</body>
</html>
