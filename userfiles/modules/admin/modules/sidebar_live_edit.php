<?php if (isset($_COOKIE['mw_basic_mode']) AND $_COOKIE['mw_basic_mode'] == '1') {
    return;
} ?>
<div id="modules-and-layouts-sidebar" class="modules-and-layouts-holder">
    <div id="mw-modules-layouts-tabsnav">
        <a href="javascript:mw.liveEditSettings.hide();" class="mw-close-sidebar-btn"><i class="mw-icon-close"></i></a>


        <div class="mw-live-edit-sidebar-tabs-wrapper">
            <a href="javascript:;" title="<?php _e("Open/Close menu"); ?>" data-id="mw-toolbar-show-sidebar-btn"
               class="sidebar-toggler">
                <div class="i-holder">
                    <span class="mw-m-menu-button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </a>
            <div class="mw-live-edit-sidebar-tabs mw-normalize-css">
                <a href="javascript:;" class="tabnav active tip" data-tip="<?php _e("Layouts"); ?>" data-tipposition="left-center"><i class="mwi-desktop-plus"></i> </a>
                <a href="javascript:;" class="tabnav tip" data-tip="<?php _e("Modules"); ?>" data-tipposition="left-center"><i class="mwi-folder"></i></a>
                <a href="javascript:;" class="tabnav tip" onclick="mw.liveEditWidgets.loadTemplateSettings('<?php print api_url() ?>module?id=template_settings_admin&live_edit=true&module_settings=true&type=settings/template&autosize=false&content_id=<?php print CONTENT_ID ?>')" data-tip="<?php _e("Template Settings"); ?>" data-tipposition="left-center"><i class="mwi-cog"></i></a>
                <a href="javascript:;" class="tabnav tip mw-lscsse-tab"
                   onclick="mw.liveEditWidgets.cssEditorInSidebarAccordion()"
                   data-tip="<?php _e("Visual Editor"); ?>"
                   data-tipposition="left-center">
                    <i class="mw-liveedit-css-editor-icon">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="31.7 -19.3 86.6 78.2" style="enable-background:new 31.7 -19.3 86.6 78.2;" xml:space="preserve">
                            <path d="M45.2-17.2c-1.1,0-2,0.9-2,2v9.7C37.2-4.6,33.1,1,34,7c0.7,4.7,4.4,8.4,9.2,9.2v38.6c0,1.1,0.9,2,2,2
                c1.1,0,2-0.9,2-2V16.1c6-1.1,9.9-6.9,8.8-12.8c-0.8-4.5-4.3-7.9-8.8-8.8v-9.7C47.2-16.3,46.3-17.2,45.2-17.2z"/>
                            <path d="M105.2-17.2c-1.1,0-2,0.9-2,2v8.5c-6,0.9-10.1,6.6-9.2,12.6c0.7,4.7,4.4,8.4,9.2,9.2v39.7c0,1.1,0.9,2,2,2
                s2-0.9,2-2V15c6-1.1,9.9-6.9,8.8-12.8c-0.8-4.5-4.3-7.9-8.8-8.8v-8.6C107.2-16.3,106.3-17.2,105.2-17.2z"/>
                            <path d="M75.2-17.2c-1.1,0-2,0.9-2,2v37.9c-6,0.9-10.1,6.6-9.2,12.6c0.7,4.7,4.4,8.4,9.2,9.2v10.4c0,1.1,0.9,2,2,2
                s2-0.9,2-2V44.4c6-1.1,9.9-6.9,8.8-12.8c-0.8-4.5-4.3-7.9-8.8-8.8v-37.9C77.2-16.3,76.3-17.2,75.2-17.2z"/>
            </svg>

                    </i>
                </a>
            </div>
        </div>


        <div class="mw-ui-box mw-scroll-box" id="mw-sidebar-modules-and-layouts-holder">
            <div class="tabitem mw-normalize-css">
                <div class="mw-live-edit-tab-title layouts">
                    <h6>Layouts</h6>
                    <div class="mw-liveedit-sidebar-search-wrapper">
                        <label for="search-input">
                            <i class="mw-icon-search" aria-hidden="true"></i>
                        </label>
                        <input onkeyup="mwSidebarSearchItems(this.value, 'layouts')" class="form-control input-lg" placeholder="Search for Layouts" autocomplete="off" spellcheck="false" autocorrect="off" tabindex="1" data-id="mw-sidebar-search-input-for-modules-and-layouts">
                        <a href="javascript:mwSidebarSearchClear('layouts');" class="mw-sidebar-search-clear-x-btn mw-icon-close" aria-hidden="true" style="display: none;"></a>
                    </div>
                    <p class="mw-search-no-results" ><?php _e("No results were found"); ?></p>
                </div>
                <div class="mw-ui-box-content">
                    <?php if (is_post() or is_product()) { ?>
                        <div data-xmodule type="admin/modules/list_layouts" id="mw-sidebar-layouts-list" hide-dynamic="true"></div>
                    <?php } else { ?>
                        <div data-xmodule type="admin/modules/list_layouts" id="mw-sidebar-layouts-list"></div>
                    <?php } ?>
                </div>
            </div>

            <div class="tabitem mw-normalize-css" style="display: none">
                <div class="mw-live-edit-tab-title modules">
                    <h6>Modules</h6>
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
                    <p class="mw-search-no-results"><?php _e("No results were found"); ?></p>
                </div>
                <div class="mw-ui-box-content">
                    <div data-xmodule type="admin/modules/list" id="mw-sidebar-modules-list"></div>
                </div>
            </div>
            <div class="tabitem mw-normalize-css" style="display: none;">
                <div class="mw-live-edit-tab-title">
                    <h6>Template settings</h6>
                </div>

                <?php if (file_exists(TEMPLATE_DIR . 'template_settings.php')) { ?>
                    <div id="mw-live-edit-sidebar-settings-iframe-holder-template-settings" class="mw-live-edit-sidebar-iframe-holder"></div>
                <?php } ?>

            </div>
            <div class="tabitem ">
                <div class="mw-live-edit-tab-title">
                    <h6>Visual editor</h6>
                </div>
                <div id="mw-css-editor-sidebar-iframe-holder" class="  mw-live-edit-sidebar-iframe-holder"></div>
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



                $('#mw-modules-layouts-tabsnav .tabnav').on('mouseup touchend', function () {

                    $('#modules-and-layouts-sidebar .mw-ui-box').scrollTop(0);
                    var active = $(this).hasClass('active');
                    if(!active) {
                        mw.liveEditSettings.show();
                    } else{
                        mw.liveEditSettings.toggle();
                    }

                });


                $("#mw-sidebar-modules-and-layouts-holder").on("mousedown", function (e) {
                    if (e.target.nodeName != 'INPUT' && e.target.nodeName != 'SELECT' && e.target.nodeName != 'OPTION' && e.target.nodeName != 'CHECKBOX') {
                        e.preventDefault();
                    }
                });
                mw.dropdown();
                mw.wysiwyg.init("#mw-sidebar-modules-and-layouts-holder .mw_editor_btn");
                mw.wysiwyg.dropdowns();

                $(".mw-live-edit-sidebar-tabs-wrapper").on('click', function (e) {
                    if(e.target === this) {
                        mw.liveEditSettings.toggle();
                    }
                })


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
            mw.liveEditDynamicTemp = {};
        </script>

        <style type="text/css" id="mw-dynamic-css">

        </style>
    </div>
