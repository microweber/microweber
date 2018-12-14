<div id="modules-and-layouts-sidebar" class="modules-and-layouts-holder">
    <a href="javascript:;" title="<?php _e("Open/Close menu"); ?>" data-id="mw-toolbar-show-sidebar-btn"
       class="sidebar-toggler">
        <div class="i-holder">
            <i class="mwi-hamb"></i>
        </div>
    </a>
    <style>
        .mw-close-sidebar-btn {
            position: absolute;
            right: 0;
            top: 0;
            padding: 2px 6px;
            z-index: 9999;
            background: #fff;
            border-bottom-left-radius: 6px;
            border-left: 1px solid;
            border-bottom: 1px solid;
        }

        .mw-close-sidebar-btn:hover {
            background: #0086db;
            color: #fff;
        }
    </style>

    <div id="mw-modules-layouts-tabsnav">
        <a href="javascript:mw.liveEditSettings.hide();" class="mw-close-sidebar-btn"><i class="mw-icon-close"></i></a>


        <div class="mw-live-edit-sidebar-tabs mw-normalize-css">
            <a href="javascript:;" class="tabnav active"><i class="mwi-desktop-plus"></i> <?php _e("Layouts"); ?></a>
            <a href="javascript:;" class="tabnav"><i class="mwi-folder"></i> <?php _e("Modules"); ?></a>
            <a href="javascript:;" class="tabnav"><i class="mwi-cog"></i> <?php _e("Settings"); ?></a>
        </div>


        <div class="mw-ui-box mw-scroll-box" id="mw-sidebar-modules-and-layouts-holder">
            <div class="tabitem mw-normalize-css">
                <div class="mw-live-edit-tab-title layouts">
                    <div class="mw-liveedit-sidebar-search-wrapper">
                        <label for="search-input">
                            <i class="mw-icon-search" aria-hidden="true"></i>
                        </label>

                        <input
                                onkeyup="mwSidebarSearchItems(this.value, 'layouts')"
                                class="form-control input-lg"
                                placeholder="Search for Layouts"
                                autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1"
                                data-id="mw-sidebar-search-input-for-modules-and-layouts">

                        <a
                                href="javascript:mwSidebarSearchClear('layouts');"
                                class="mw-sidebar-search-clear-x-btn mw-icon-close"
                                aria-hidden="true"
                                style="display: none;"></a>
                    </div>

                    <p class="mw-search-no-results"
                       style="margin: 35px 0 15px 0; display: none; text-align: center;"><?php _e("No results were found"); ?></p>
                </div>
                <div class="mw-ui-box-content">
                    <div data-xmodule type="admin/modules/list_layouts" id="mw-sidebar-layouts-list"></div>
                </div>
            </div>

            <div class="tabitem mw-normalize-css" style="display: none">
                <div class="mw-live-edit-tab-title modules">
                    <div class="mw-liveedit-sidebar-search-wrapper">


                        <label for="search-input">
                            <i class="mw-icon-search" aria-hidden="true"></i>
                        </label>

                        <input onkeyup="mwSidebarSearchItems(this.value, 'modules')" class="form-control input-lg"
                               placeholder="Search for Modules"
                               autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1"
                               data-id="mw-sidebar-search-input-for-modules-and-layouts">

                        <a href="javascript:mwSidebarSearchClear('modules');"
                           class="mw-sidebar-search-clear-x-btn mw-icon-close"
                           aria-hidden="true" style="display: none;"></a>


                    </div>

                    <p class="mw-search-no-results"
                       style="margin: 35px 0 15px 0; display: none; text-align: center;"><?php _e("No results were found"); ?></p>
                </div>
                <div class="mw-ui-box-content">
                    <div data-xmodule type="admin/modules/list" id="mw-sidebar-modules-list"></div>
                </div>
            </div>


            <div class="mw-ui-box-content tabitem mw-normalize-css  mw-live-edit-sidebar-iframe-holder"
                 style="display: none;">


                <div class="mw-accordion" data-options="openFirst: false">


                    <?php if (file_exists(TEMPLATE_DIR . 'template_settings.php')) { ?>
                        <script>
                            mw.___load_template_settings_iframe_in_sidebar_accordeon = function () {
                                var html = ' <iframe id="mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame" style="height:500px" class="mw-live-edit-sidebar-settings-iframe"   src="<?php print api_url() ?>module?id=template_settings_admin&live_edit=true&module_settings=true&type=settings/template&autosize=false&content_id=<?php print CONTENT_ID ?>"></iframe>'
                                if ($("#mw-live-edit-sidebar-settings-iframe-holder-template-settings-frame").length == 0) {
                                    $('#mw-live-edit-sidebar-settings-iframe-holder-template-settings').html(html);
                                }

                            }
                        </script>
                        <div class="mw-accordion-item ">
                            <div class="mw-ui-box-header mw-accordion-title "
                                 onclick="mw.___load_template_settings_iframe_in_sidebar_accordeon()">
                                <div class="header-holder">
                                    <i class="mai-setting2"></i> Template settings
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content ">
                                <div id="mw-live-edit-sidebar-settings-iframe-holder-template-settings"></div>
                            </div>
                        </div>


                    <?php } ?>


                     <div class="mw-accordion-item" style="display: none;">
                        <div class="mw-ui-box-header mw-accordion-title">
                            <div class="header-holder">
                                <i class="mai-setting2"></i> CSS Editor
                            </div>
                        </div>
                        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                            <div class="mw-ui-box-content css-editor-holder">

                                <div id="mw-css-editor-selected"></div>
                               <?php /* <iframe src="<?php print site_url('editor_tools'); ?>" id="mw-css-editor"></iframe> */ ?>

                            </div>
                        </div>
                    </div>

                </div>


              <?php

              /*  <?php if (file_exists(TEMPLATE_DIR . 'template_settings.php')) { ?>

                    <a class="mw-ui-btn" href="javascript:mw.tools.toggle_template_settings();">Open template
                        settings</a>

                    <!--
 <iframe class="mw-live-edit-sidebar-settings-iframe"   data-src="<?php print api_url() ?>module?id=template_settings_admin&live_edit=true&module_settings=true&type=settings/template&autosize=false&content_id=<?php print CONTENT_ID ?>"></iframe>
-->

                <?php } ?>
            </div>*/


              ?>


            <?php
            /* <div class="mw-ui-box-content tabitem mw-normalize-css  mw-live-edit-sidebar-iframe-holder">
                <?php if (file_exists(ACTIVE_TEMPLATE_DIR . 'template_settings.php')) { ?>

                    <a class="mw-ui-btn" href="javascript:load_template_settings_iframe();">Template settings</a>


                    <?php d(ACTIVE_TEMPLATE_DIR . 'template_settings.php') ?>

                    <script>
                        function load_template_settings_iframe() {

                        var html =  '  <iframe class="mw-live-edit-sidebar-settings-iframe" data-src="<?php print api_url() ?>module?id=settings/template&live_edit=true&module_settings=true&type=settings/template&autosize=false"></iframe>'

                            $('.mw-live-edit-sidebar-iframe-holder').html(html);

                        }

                    </script>

                <?php } ?>
            </div>
*/

            ?>





            <?php

            /*<div class="mw-ui-box-content tabitem module-settings-holder" id="mw-sidebar-quick-edit-items">

                    <div id="js-live-edit-side-wysiwyg-editor-holder" class="mw-defaults mw-live-edit-component-options" <?php print lang_attributes(); ?>>

                        <div class="mw-defaults mw_editor">


                            <div class="mw-ui-row">
                                <?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg_sidebar.php'; ?>

                            </div>
                        </div>
                    </div>

                    <div id="js-live-edit-image-settings-holder" class="mw-defaults mw-live-edit-component-options">

                    </div>
                    <div id="js-live-edit-module-settings-holder" class="mw-defaults mw-live-edit-component-options">
                        <div id="js-live-edit-module-settings-items"></div>
                    </div>

                    <div id="js-live-edit-icon-settings-holder" class="mw-defaults mw-live-edit-component-options mw-ui-box mw-ui-box-content">
                        icon
                    </div>


                </div>*/

            ?>



        </div>
    </div>

    <script>

        mw.require('prop_editor.js');
        mw.require('color.js');
        //mw.require('libs/html2canvas/html2canvas.min.js');

        function mwSidebarSearchClear(what) {
            $('[data-id="mw-sidebar-search-input-for-modules-and-layouts"]').val('');
            $('.mw-sidebar-search-clear-x-btn', '.' + what).hide();
            mwSidebarSearchItems('', what);
            $('.mw-search-no-results', '.' + what).hide();
        }

        function mwSidebarSearchItems(value, what) {
            if (what == 'modules') {
                var obj = mw.$("#mw-sidebar-modules-list .modules-list > li");
            } else {
                var obj = mw.$("#mw-sidebar-layouts-list .modules-list > li");
            }
            if (!value) {
                $('.mw-sidebar-search-clear-x-btn', '.' + what).hide();
                obj.show();
                return;
            }

            $('.mw-sidebar-search-clear-x-btn', '.' + what).show();

            var value = value.toLowerCase();

            var numberOfResults = 0;

            var yourArray = [];
            $(obj).each(function () {

                var show = false;

                var description = $(this).attr('description') || false;
                var description = description || $(this).attr('data-filter');
                var title = $(this).attr('title') || false;
                var template = $(this).attr('template') || false;

                if (
                    !!title && title.toLowerCase().contains(value)
                    || (!!description && description.toLowerCase().contains(value))
                    || (!!template && template.toLowerCase().contains(value))

                ) {
                    var show = true;
                }

                if (!show) {
                    $(this).hide();

                } else {
                    $(this).show();
                    numberOfResults++;
                }
            });

            if (numberOfResults == 0) {
                $('.mw-search-no-results', '.' + what).show();
            } else {
                $('.mw-search-no-results', '.' + what).hide();
            }

        }


        $(document).ready(function () {
            mw.sidebarSettingsTabs = mw.tabs({
                nav: '#mw-modules-layouts-tabsnav  .tabnav',
                tabs: '#mw-modules-layouts-tabsnav .tabitem'
            });


            $('#mw-modules-layouts-tabsnav .tabnav').on('click', function () {
                $('#modules-and-layouts-sidebar .mw-ui-box').scrollTop(0);
            });


            $("#mw-sidebar-modules-and-layouts-holder").on("mousedown touchstart", function (e) {
                if (e.target.nodeName != 'INPUT' && e.target.nodeName != 'SELECT' && e.target.nodeName != 'OPTION' && e.target.nodeName != 'CHECKBOX') {
                    e.preventDefault();
                }
            });
            mw.dropdown();
            mw.wysiwyg.init("#mw-sidebar-modules-and-layouts-holder .mw_editor_btn");
            mw.wysiwyg.dropdowns();


        });

        var setScrollBoxes = function () {
            var root = document.querySelector('#modules-and-layouts-sidebar');
            if (root !== null) {
                var el = root.querySelectorAll('.mw-scroll-box');
                for (var i = 0; i < el.length; i++) {
                    var h = (innerHeight - 50 - ($(el[i]).offset().top - $("#live_edit_side_holder").offset().top));
                    el[i].style.height = h + 'px'
                }
            }
        }

        mw.on('liveEditSettingsReady', function () {
            setScrollBoxes();
            setTimeout(function () {
                mw.drag.toolbar_modules();
                $("#mw-sidebar-layouts-list, #mw-sidebar-modules-list").removeClass("module");

            }, 333)

        });

        $(window).on('resize orientationchange', function () {
            setScrollBoxes()
        });


    </script>


  <script>


        $(document).ready(function () {


            CSSEditorSchema = [
                {
                    interface: 'block',
                    class: '',
                    content: 'Size'
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Width',
                            id: 'width'
                        },
                        {
                            interface: 'size',
                            label: 'Height',
                            id: 'height'
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Min width',
                            id: 'minWidth'
                        },
                        {
                            interface: 'size',
                            label: 'Max width',
                            id: 'maxWidth'
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Min height',
                            id: 'minHeight'
                        },
                        {
                            interface: 'size',
                            label: 'Max height',
                            id: 'maxHeight'
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: '',
                    content: 'Margin'
                },
                {
                    interface: 'quatro',
                    label: [' top', 'right', 'bottom', 'left'],
                    id: 'margin'
                },
                {
                    interface: 'block',
                    class: '',
                    content: 'Padding'
                },
                {
                    interface: 'quatro',
                    label: ['top', 'right', 'bottom', 'left'],
                    id: 'padding'
                },

                {
                    interface: 'block',
                    class: '',
                    content: '<hr>Font'
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Size',
                            id: 'fontSize'
                        },
                        {
                            interface: 'select',
                            label: 'Weight',
                            id: 'fontWeight',
                            options: ['inherit', 'normal', 'bold', 'bolder', 'lighter', 100, 200, 300, 400, 500, 600, 700, 800, 900]
                        }

                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'select',
                            label: 'Style',
                            id: 'fontStyle',
                            options: ['italic', 'normal']
                        },
                        {
                            interface: 'color',
                            label: 'Color',
                            id: 'color'
                        }

                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'select',
                            label: 'Text transform',
                            id: 'textTransform',
                            options: ['none', 'uppercase', 'lowercase', 'capitalize']
                        },
                        {
                            interface: 'size',
                            label: 'Line Height',
                            id: 'lineHeight'
                        }
                    ]
                },
                {
                    interface: 'hr'
                },
                {
                    interface: 'block',
                    content: 'Border radius'
                },
                {
                    interface: 'quatro',
                    id: 'borderRadius',
                    label: ['Top Left', 'Top Right', 'Bottom Left', 'Bottom Right']
                },
                {
                    interface: 'hr'
                },
                {
                    interface:'block',
                    content:'Background'
                },
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'backgroundColor'
                },
                {
                    interface: 'file',
                    id: 'backgroundImage',
                    label: 'Image',
                    types: 'images'
                },
                {
                    interface: 'select',
                    id: 'backgroundRepeat',
                    label: 'Repeat',
                    options: ['no-repeat', 'repeat-x', 'repeat-y', 'repeat']
                },
                {
                    interface: 'select',
                    id: 'backgroundSize',
                    label: 'Size',
                    options: ['auto', 'cover', {title: 'fit', value: 'contain'}, {title: 'scale', value: '100% 100%'}]
                },
                {
                    interface: 'select',
                    id: 'backgroundPosition',
                    label: 'Position',
                    options: [
                        'center',
                        {title: 'Top Left', value: '0 0'},
                        {title: 'Top Center', value: '50% 0'},
                        {title: 'Top Right', value: '100% 0'},
                        {title: 'Middle Left', value: '0 50%'},
                        {title: 'Middle Right', value: '100% 50%'},
                        {title: 'Middle Right', value: '100% 50%'},
                        {title: 'Bottom Left', value: '0 100%'},
                        {title: 'Bottom Center', value: '50% 100%'},
                        {title: 'Bottom Right', value: '100% 100%'}

                    ]
                },
                {
                    interface: 'hr'
                }

                /*{
                    interface: 'select',
                    id: 'backgroundClip',
                    label: 'Clip',
                    options: ['border-box', 'padding-box', 'content-box']
                },
                */
                /*{
                    interface: 'block',
                    content: 'Element Shadow',
                },
                {
                    interface: 'shadow',
                    id: 'boxShadow'
                }*/

            ];

            $("#mw-css-editor").css({
                position:'absolute',
                top:0,
                left:0,
                width:'100%',
                height:1460
            });
            
            $("#mw-css-editor").on('load', function(){
                this.contentWindow.mw.require('liveedit.css')
                this.contentWindow.mw.$('body').css('background', '#fff');
                mw.elementCSSEditor = new mw.propEditor.schema({
                    schema: CSSEditorSchema,
                    element: '#mw-css-editor'
                });

                var _prepareCSSValue = function(property, value){
                    if(property === 'backgroundImage'){
                        return 'url(' + value + ')';
                    }
                    return value;
                };
                var _setElementStyle = function(p, value){
                    var val = _prepareCSSValue(p, value);
                    var css = {};
                    css[p] = val;
                    if(p === 'backgroundClip') {
                        css = {
                            'background-clip':val,
                            '-webkit-background-clip':val
                        };
                    }
                    mw.$(mw.elementCSSEditor.currentElement).css(css);
                };
                $(mw.elementCSSEditor).on('change', function (event, property, value) {
                    if($.isArray(value)){
                        value = value[0];
                    }
                    _setElementStyle(property, value);
                    mw.$(mw.elementCSSEditor.currentElement).attr('staticdesign', true);
                });

            });




            $(document.body).on("data-click", function (event) {

                var el = event.target;
                if(mw.tools.hasParentsWithClass(el, 'mw-control-box')){
                    return;
                }
                mw.elementCSSEditor.currentElement = null;

                $("#mw-css-editor-selected").css('backgroundImage', 'none')

                if(el.id && !mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['mw-defaults'])){
                    mw.elementCSSEditor.currentElement = el;
                }
                else{
                    mw.tools.foreachParents(el, function(loop){
                        if(this.id && !mw.tools.hasAnyOfClassesOnNodeOrParent(this, ['mw-defaults'])){
                            mw.elementCSSEditor.currentElement = this;
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                if(!mw.elementCSSEditor.currentElement){
                    return;
                }



                /*html2canvas(mw.elementCSSEditor.currentElement).then(function(canvas) {
                    $("#mw-css-editor-selected").css('background-image', 'url(' + canvas.toDataURL() + ')');
                });*/



                var css = getComputedStyle(el);
                var bgimg = css.backgroundImage;
                if(bgimg.indexOf('url(') !== -1){
                    bgimg = bgimg.split('(')[1].split(')')[0]
                }
                var val = {
                    margin: (css.marginTop + ' ' + css.marginRight + ' ' + css.marginBottom + ' ' + css.marginLeft),
                    padding: (css.paddingTop + ' ' + css.paddingRight + ' ' + css.paddingBottom + ' ' + css.paddingLeft),
                    fontSize: el.style.fontSize || css.fontSize,
                    letterSpacing: css.letterSpacing,
                    fontWeight: css.fontWeight,
                    fontStyle: css.fontStyle,
                    lineHeight: css.lineHeight,
                    textTransform: css.textTransform,
                    backgroundClip: css.backgroundClip,
                    color: mw.color.rgbToHex(css.color),
                    backgroundColor: mw.color.rgbToHex(css.backgroundColor),
                    backgroundImage: bgimg,
                    backgroundRepeat: css.backgroundRepeat,
                    backgroundSize: css.backgroundSize,
                    backgroundPosition: css.backgroundPosition,
                    width: css.width,
                    minWidth: css.minWidth,
                    maxWidth: css.maxWidth,
                    minHeight: css.minHeight,
                    maxHeight: css.maxHeight,
                    height: css.height,
                    boxShadow: css.boxShadow,
                    borderRadius: (css.borderTopLeftRadius + ' ' + css.borderTopRightRadius + ' ' + css.borderBottomLeftRadius + ' ' + css.borderBottomRightRadius),
                };
                mw.elementCSSEditor.setValue(val);
            });




        });


    </script>


        <script>



            mw.liveEditDynamicTemp = {};

        </script>

        <style type="text/css" id="mw-dynamic-css">



        </style>



</div>