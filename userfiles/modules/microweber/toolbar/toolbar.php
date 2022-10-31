<?php
$is_quick_edit = false;
if (defined('QUICK_EDIT')) {
    $is_quick_edit = QUICK_EDIT;
}

if ($is_quick_edit == true) {
    return include(__DIR__ . DS . 'toolbar_quick.php');
}

if (isset($_COOKIE['mw_exp'])) {
    return include(__DIR__ . DS . 'toolbar2.php');
}

?>
<?php if (!isset($_GET['preview'])) { ?>
    <script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-core.js"></script>
    <script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-cssclassapplier.js"></script>
    <script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-selectionsaverestore.js"></script>
    <script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-serializer.js"></script>
    <script type="text/javascript">mw.lib.require("jqueryui");</script>

    <script type="text/javascript">
        mw.settings.liveEdit = true;
        mw.require("liveadmin.js");
        //   mw.require("events.js");
        mw.require("url.js");
        //mw.require("tools.js");
        mw.require("wysiwyg.js");
        mw.require("css_parser.js");
        mw.require("filepicker.js");

        mw.require("forms.js");
        mw.require("files.js");
        mw.require("content.js");
        mw.require("session.js");
        mw.require("<?php print mw()->template->get_liveeditjs_url()  ?>");
        mw.require("upgrades.js");


    </script>

    <?php if (config('app.debug')) { ?>

        <script type="text/javascript">
            window.__onerror_alert_shown = false;
            window.onerror = function (msg, url, lineNo, columnNo, error) {
                if ((typeof(msg) != 'undefined') && !msg.contains('ResizeObserver') && !window.__onerror_alert_shown) {
                    var string = msg.toLowerCase();
                    var substring = "script error";
                    if (string.indexOf(substring) > -1) {
                        alert('Script Error: See Browser Console for Detail');
                    } else {
                        var message = [
                            'Message: ' + msg,
                            'URL: ' + url,
                            'Line: ' + lineNo,
                            'Column: ' + columnNo,
                            'Error object: ' + JSON.stringify(error)
                        ].join(' \n ');

                        console.log(message);
                    }

                    return false;
                }
                window.__onerror_alert_shown = true;


            };

        </script>
    <?php } ?>


    <script type="text/javascript">
        $(window).bind('load', function () {


            $(window).on('saveStart adminSaveStart', function () {
                mw.$("#main-save-btn,.btn-save").html('<?php _e("Saving"); ?>...');
            });
            $(window).on('saveEnd adminSaveEnd', function () {
                mw.$("#main-save-btn,.btn-save").html('<?php _e("Save"); ?>');
                mw.notification.success('<?php _ejs("All changes are saved"); ?>.')
            });


        });

    </script>

    <?php print app()->template->admin->getLiveEditTemplateHeadHtml(); ?>

    <?php

    $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFontsAsString();


    ?>
    <?php
    $disable_keyboard_shortcuts = get_option('disable_keyboard_shortcuts', 'website');

    ?>

    <?php if ($disable_keyboard_shortcuts): ?>
        <script type="text/javascript">
            mw.settings.live_edit_disable_keyboard_shortcuts = true;
        </script>
    <?php endif; ?>

    <?php
    $open_module_settings_in_sidebar = get_option('open_module_settings_in_sidebar', 'live_edit');
    ?>


    <script type="text/javascript">
        <?php if ($open_module_settings_in_sidebar): ?>
        mw.settings.live_edit_open_module_settings_in_sidebar = true;
        <?php else: ?>
        mw.settings.live_edit_open_module_settings_in_sidebar = false;
        <?php endif; ?>
    </script>


    <script>
        $(document).ready(function () {

            mw.tools.dropdown();
            mw_save_draft_int = self.setInterval(function () {
                mw.drag.saveDraft();
            }, 1000);


            $(document.body).addClass('notranslate');
        });

        if (typeof(mw.wysiwyg) != 'undefined') {
            mw.wysiwyg.initExtendedFontFamilies("<?php print $enabled_custom_fonts?>");
        }
    </script>
    <?php
    $back_url = admin_url() . 'view:content';
    if (defined('CONTENT_ID')) {
        if ((!defined('POST_ID') or POST_ID == false) and !defined('PAGE_ID') or PAGE_ID != false and PAGE_ID == CONTENT_ID) {
          //  $back_url .= '#action=showposts:' . PAGE_ID;
            $back_url = route('admin.page.edit', PAGE_ID);

        } else {
          //  $back_url .= '#action=editpage:' . CONTENT_ID;
            $back_url = route('admin.content.edit', CONTENT_ID);

        }
    } else if (isset($_COOKIE['back_to_admin'])) {
        $back_url = $_COOKIE['back_to_admin'];

    }

    $user = get_user();

    ?>

    <div class="mw-helpinfo semi_hidden">
        <div class="mw-help-item" data-for="#live_edit_toolbar" data-pos="bottomcenter">
            <div style="width: 300px;">
                <p style="text-align: center"><img src="<?php print mw_includes_url(); ?>img/dropf.gif" alt=""/></p>
                <p> <?php _e('You can easily grab any Module and insert it in your content.'); ?> </p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {



            if (!mw.cookie.get('mw-back-to-live-edit')) {
                mw.cookie.set('mw-back-to-live-edit', true)
            }


            /*       function mw_live_edit_opensidebar() {
                       if (mw.liveEditSettings.active) {
                       } else {
                           $('#live_edit_side_holder').addClass('sidebar_opened');
                           $('a[data-id="mw-toolbar-show-sidebar-btn"]').addClass('opened');
                           mw.cookie.set("show-sidebar-layouts", '1');
                       }
                   }

                   var $li = $("#sidebar-hidden-area").hover(
                       function () {
                           var self = this;
                           hovertimer = setTimeout(function () {

                               mw_live_edit_opensidebar();

                           }, 1700);
                       },
                       function () {
                           clearTimeout(hovertimer);
                       }
                   );*/
        });

    </script>


    <script>



        mw.on('liveEditSettingsReady', function () {







            //     $('a','#live_edit_toolbar_holder').off('click')


            mw.drag.init();
            $('[data-id="mw-toolbar-show-sidebar-btn"]').click(function () {
                mw.liveEditSettings.toggle();
            });
            var params = mw.url.getUrlParams(location.href);

            if (typeof mw.cookie.get('show-sidebar-layouts') === 'undefined') {


                if (params.sidebar == 1) {
                    mw.liveEditSettings.show();
                }
                else if (!params.sidebar) {
                    mw.liveEditSettings.hide();
                }
            }

            if (mw.cookie.get('show-sidebar-layouts') == 1) {
                mw.liveEditSettings.show();
                $('#live_edit_side_holder').addClass('sidebar_opened');
                $('a[data-id="mw-toolbar-show-sidebar-btn"]').addClass('opened');
            }
            else {
                $('#live_edit_side_holder').removeClass('sidebar_opened');
                $('a[data-id="mw-toolbar-show-sidebar-btn"]').removeClass('opened');
            }
            $('body').prepend('<div id="sidebar-hidden-area"></div>');
        });

        $(window).on("load", function () {
            mw.liveEditSettings = new mw.controlBox({
                content: '<div class="module" type="admin/modules/sidebar_live_edit"></div>',
                position: !mw.tools.isRtl() ? 'right' : 'right',
                id: 'live_edit_side_holder',
                closeButton: true
            });


            $(mw.liveEditSettings)
                .on('ControlBoxShow', function () {
                    $(window).trigger('collapseNavReInit');
                    $(document.body).addClass('has-opened-sidebar');
                    $('a[data-id="mw-toolbar-show-sidebar-btn"]').addClass('opened');
                    mw.cookie.set("show-sidebar-layouts", '1');
                })
                .on('ControlBoxHide', function () {
                    $(document.body).removeClass('has-opened-sidebar');
                    $('a[data-id="mw-toolbar-show-sidebar-btn"]').removeClass('opened');
                    mw.cookie.set("show-sidebar-layouts", '0');
                    $(window).trigger('collapseNavReInit');

                });

            mw.tools.loading(mw.liveEditSettings.box);

            setTimeout(function () {

                mw.reload_module('#' + mw.liveEditSettings.id + ' div[type]', function () {
                    $("[data-xmodule]").addClass("module")
                    setTimeout(function () {
                        settingsLoaded = 0;
                        var all = mw.$("#modules-and-layouts-sidebar [data-xmodule], #modules-and-layouts-sidebar [data-src]");
                        all.each(function () {
                            var src = $(this).dataset("src");
                            if (src) {
                                $(this).on("load error", function () {
                                    settingsLoaded++;
                                    if (settingsLoaded === all.length) {
                                        $("#live_edit_side_holder .module").removeClass("module")
                                        setTimeout(function(){
                                            mw.trigger('liveEditSettingsReady')
                                        }, 300)

                                    }
                                });
                                this.src = src;
                            }
                            else {
                                mw.tools.addClass(this, 'module');
                                mw.reload_module(this, function () {
                                    settingsLoaded++;
                                    if (settingsLoaded === all.length) {
                                        $("#live_edit_side_holder .module").removeClass("module")
                                        setTimeout(function(){
                                            mw.trigger('liveEditSettingsReady')
                                        }, 300)
                                    }
                                })
                            }
                        })

                    }, 500)
                });

            }, 1000)
        })



        mw.open_content_revisions_dialog = function(cont_id) {

            if(typeof(cont_id) === 'undefined'){
                return;
            }




            var opts = {};
            opts.title = 'Content versions';
            opts.width = '800';
            opts.height =  '480';
            opts.liveedit = true;
            opts.autoHeight = true;
            opts.maxHeightWindowScroll = '800px';

            opts.overlay = false;
            opts.mode = 'modal';

            var additional_params = {};
            additional_params.content_id = cont_id;
            additional_params.show_btn_for_find_element = true;
            additional_params.from_url_string = '<?php print url_string() ?>';
            if(additional_params.from_url_string === ''){
                additional_params.from_url_string_home = 1;
            }

            mw.tools.open_global_module_settings_modal('editor/content_revisions/list_for_content', '#mw_admin_content_revisions_list_for_content_popup_modal_module', opts, additional_params);








        };



    </script>

    <div class="mw-defaults" id="live_edit_toolbar_holder" <?php print lang_attributes(); ?>>
        <div id="live_edit_toolbar">
            <div id="mw-text-editor" class="mw-defaults mw_editor">
                <div class="toolbar-sections-tabs">
                    <ul>
                        <li class="create-content-dropdown">
                            <a href="javascript:;" class="tst-logo" title="<?php print addslashes(mw()->ui->brand_name()) ?>">
                                <?php if (mw()->ui->logo_live_edit != false) : ?>
                                    <span class="white-label-logo" style="background-image:url('<?php print mw()->ui->logo_live_edit ?>');"></span>
                                <?php else: ?>
                                    <span class="mw-icon-mw"></span>
                                <?php endif; ?>
                                <span class="mw-icon-dropdown"></span>
                            </a>
                            <div class="mw-dropdown-list create-content-dropdown-list">
                                <div class="mw-dropdown-list-search">
                                    <input type="text" class="mw-ui-field w100 mwtb-search mw-dropdown-search" placeholder="Search content"/>
                                </div>
                                <?php
                                $pt_opts = array();
                                $pt_opts['link'] = "<a href='{link}#tab=pages'>{title}</a>";
                                $pt_opts['list_tag'] = "ul";
                                $pt_opts['ul_class'] = "";
                                $pt_opts['list_item_tag'] = "li";
                                $pt_opts['active_ids'] = content_id();
                                $pt_opts['limit'] = 1000;
                                $pt_opts['active_code_tag'] = 'class="active"';
                                mw()->content_manager->pages_tree($pt_opts);
                                ?>
                                <a id="backtoadminindropdown"
                                   href="<?php print $back_url; ?>"
                                   ><span class="mdi mdi-arrow-left"></span><span><?php _e("Back to Admin"); ?></span> </a>
                            </div>
                        </li>
                        <?php event_trigger('live_edit_toolbar_menu_start'); ?>
                        <li class="create-content-dropdown mw-toolbar-btn-menu">


                            <a href="javascript:;"
                               class="mw-ui-btn mw-ui-btn-medium mw-dropdown-button mw-toolbar-add-new-content-ctrl"
                               title="Create or manage your content">
                                <i class="mdi mdi-plus mr-0"></i><span> <?php _e("Add New"); ?> </span>
                            </a>
                            <ul class="mw-dropdown-list create-content-dropdown-list liveeditcreatecontentmenu"
                                style="width: 200px; text-transform:uppercase;top: 51px;">
                                <?php event_trigger('live_edit_quick_add_menu_start'); ?>
                                <li>
                                    <a href="javascript:;" onclick="mw.liveedit.manageContent.edit(<?php print CONTENT_ID; ?>);">
                                        <span class="mw-icon-pen"></span>
                                        <span><?php _e("Edit current"); ?></span>
                                    </a>
                                </li>

                                <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                                <?php if (!empty($create_content_menu)): ?>
                                    <?php foreach ($create_content_menu as $type => $item): ?>
                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                        <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                                        <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                                        <?php
                                        $content_menu_url = route('admin.content.create');
                                        if (\Route::has('admin.'.$item['content_type'].'.create')) {
                                            $content_menu_url = route('admin.' . $item['content_type'] . '.create');
                                        }
                                        ?>
                                        <li>
                                            <a onclick="mw.liveedit.manageContent.edit('0','<?php print $type; ?>', '<?php print $subtype; ?>', '<?php print MAIN_PAGE_ID; ?>', '<?php print CATEGORY_ID; ?>'); return false;"
                                               href="<?php print  $content_menu_url ?>">
                                                <span class="<?php print $class; ?>"></span>
                                                <strong><?php print $title; ?></strong>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>



                                <?php event_trigger('live_edit_quick_add_menu_end'); ?>
                            </ul>

                        </li>
                        <li>

                        </li>


                        <?php event_trigger('live_edit_toolbar_menu_end'); ?>
                    </ul>
                    <div class="wysiwyg-undo-redo">
                        <div class="wysiwyg-cell-undo-redo">
            <span
                    class="liveedit_wysiwyg_prev"
                    id="liveedit_wysiwyg_main_prev"
                    title="<?php _e("Previous"); ?>"
                    onclick="mw.liveedit.toolbar.editor.slideLeft();"></span>



                        </div>
                    </div>
                </div>

                <?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg.php'; ?>
                <div id="mw-toolbar-right" class="mw-defaults">
        <span
                class="liveedit_wysiwyg_next"
                id="liveedit_wysiwyg_main_next"
                title="<?php _e("Next"); ?>"
                onclick="mw.liveedit.toolbar.editor.slideRight();"></span>
                    <div class="mw-toolbar-right-content">
                        <?php event_trigger('live_edit_toolbar_action_buttons'); ?>

                        <a id="back-to-admin-toolbar" href="<?php print $back_url; ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert" data-tip="<?php _e("Back to Admin"); ?>" data-tipposition="bottom-center">
                            <i class="mdi mdi-arrow-left"></i>
                            <?php _e("Admin"); ?>
                        </a>
                        <button disabled class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert" onclick="mw.drag.save()" id="main-save-btn"><i class="mdi mdi-floppy savebtn"></i><?php _e("Save"); ?></button>

                        <div class="mw-ui-dropdown mw-dropdown-defaultright" id="toolbar-dropdown-actions" >
                            <span class="mw-single-arrow-dropdown mw-single-arrow-dropdown-right"><span
                                        class="mw-icon-dropdown"></span></span>
                            <div class="mw-ui-dropdown-content" id="live-edit-dropdown-actions-content">
                                <ul class="mw-ui-box mw-ui-navigation">
                                    <?php event_trigger('live_edit_toolbar_action_menu_start'); ?>


                                    <?php $custom_ui = mw()->module_manager->ui('live_edit.toolbar.action_menu.start'); ?>
                                    <?php if (!empty($custom_ui)): ?>
                                        <?php foreach ($custom_ui as $item): ?>
                                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                            <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                            <li class="mw-defaults <?php print $class; ?>"
                                                <?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                                title="<?php print $title; ?>">
                                                <div class="mw-ui-btn"><?php print $html; ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <li>
                                        <a title="<?php _e("Back to Admin"); ?>" href="<?php print $back_url; ?>">
                                             <?php _e("Back to Admin"); ?>
                                        </a>
                                    </li>

                                    <?php /* <li style="display: nonex">
                                        <script>mw.userCanSwitchMode = false;</script>
                                        <?php if (!isset($user['basic_mode']) or $user['basic_mode'] != 'y') { ?>
                                            <script>mw.userCanSwitchMode = true;</script>
                                        <?php if (isset($_COOKIE['advancedmode']) and $_COOKIE['advancedmode'] == 'true') { ?>
                                            <a href="javascript:;" onclick="mw.setMode('simple');"
                                               style="display:none"><?php _e("Simple Mode"); ?></a>
                                        <?php } else { ?>
                                            <a href="javascript:;" onclick="mw.setMode('advanced');"
                                               style="display:none"><?php _e("Advanced Mode"); ?></a>
                                        <?php } ?>
                                        <?php } ?>
                                    </li> */ ?>

                                    <li>
                                        <a href="<?php print mw()->url_manager->current(); ?>?editmode=n"><?php _e("View Website"); ?></a>
                                    </li>
                                    <?php event_trigger('live_edit_toolbar_action_menu_middle'); ?>
                                    <?php if (defined('CONTENT_ID') and CONTENT_ID > 0): ?>
                                        <?php $pub_or_inpub = mw()->content_manager->get_by_id(CONTENT_ID); ?>
                                        <li class="mw-set-content-unpublish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 1): ?> style="display:none" <?php endif; ?>>
                                            <a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><span><?php _e("Unpublish"); ?></span></a>
                                        </li>
                                        <li class="mw-set-content-publish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 1): ?> style="display:none" <?php endif; ?>>
                                            <a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><span><?php _e("Publish"); ?></span></a>
                                        </li>
                                    <?php endif; ?>


                                    <li>
                                        <a><i class="mw-icon-arrowleft"></i><?php _e("Tools"); ?></a>
                                        <ul>
                                            <!--<li><a class="mw_ex_tools mw_editor_css_editor" id="mw-toolbar-css-editor-btn"><span class="mw-icon-css">{}</span><?php /*_e("CSS Editor"); */?></a></li>-->
                                            <li><a class="mw_ex_tools mw_editor_html_editor" id="mw-toolbar-html-editor-btn"><span class="mw-icon-code"></span><?php _e("Code Editor"); ?></a></li>
                                            <li><a class="mw_ex_tools" onclick="mw.open_content_revisions_dialog('<?php print CONTENT_ID; ?>')" id="mw-toolbar-content-revisions-btn"><span class="mw-icon-code"></span><?php _e("Content versions"); ?></a></li>
                                            <li><a class="mw_ex_tools mw_editor_reset_content" id="mw-toolbar-reset-content-editor-btn"><i class="mw-icon-reload"></i><span><?php _e("Reset content"); ?></span></a></li>

                                            <li><a class="mw_ex_tools" id="mw-toolbar-api-clear-cache-btn"><?php _e("Clear Cache"); ?></a></li>
                                        </ul>
                                    </li>

                                    <?php if (isset($_COOKIE['mw_basic_mode']) AND $_COOKIE['mw_basic_mode'] == '1'): ?>
                                        <li><a href="javascript:;" onclick="mw.cookie.set('mw_basic_mode', '0'); window.location.reload();"><span><?php _e("Advanced Mode"); ?></span></a></li>
                                    <?php else: ?>
                                        <li><a href="javascript:;" onclick="mw.cookie.set('mw_basic_mode', '1'); window.location.reload();"><span><?php _e("Basic Mode"); ?></span></a></li>
                                    <?php endif; ?>

                                    <li><a href="<?php print logout_url(); ?>"><i class="mw-icon-off"></i><span><?php _e("Logout"); ?></span></a></li>
                                    <?php event_trigger('live_edit_toolbar_action_menu_end'); ?>
                                </ul>
                            </div>
                        </div>
                        <a
                                class="view-website-button tip"
                                href="<?php print mw()->url_manager->current(); ?>?editmode=n"
                                data-tip="<?php _e('View Website'); ?>"
                                data-tipposition="bottom-right"></i>
                        </a>

                        <div class="Switch2AdvancedModeTip" style="display: none">
                            <div class="Switch2AdvancedModeTip-tickContainer">
                                <div class="Switch2AdvancedModeTip-tick"></div>
                                <div class="Switch2AdvancedModeTip-tick2"></div>
                            </div>
                            <?php _e('If you want to edit this section you have to switch do'); ?>
                            "<strong><?php _e('Advanced Mode'); ?></strong>".
                            <div class="Switch2AdvancedModeTiphr"></div>
                            <div style="text-align: center"><span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-green" onclick="mw.setMode('advanced');"><?php _e('Switch'); ?></span>
                                <span class="mw-ui-btn mw-ui-btn-small" onclick="$(this.parentNode.parentNode).hide();mw.doNotBindSwitcher=true;"><?php _e('Cancel'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php event_trigger('live_edit_toolbar_controls'); ?>

            <div id="mw-saving-loader"></div>
        </div>
    </div>

    <?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg_tiny.php'; ?>


    <script>
        $(window).on('load', function () {

            mw.liveedit.toolbar.editor.init();
            mw.liveedit.toolbar.editor.buttons();
            $(window).on("resize", function () {
                mw.liveedit.toolbar.editor.buttons();

            });
            mw.interval('liveEditWYSIWYG', function () {
                var n = mw.liveedit.toolbar.editor.calc.SliderNormalize(mw.liveedit.toolbar.editor.ed);
                if (!!n) {
                    // mw.liveedit.toolbar.editor.slideRight();
                }
                var edl = mw.$("#liveedit_wysiwyg");
                var ewidth = 0;
                edl.find('.wysiwyg-cell').each(function(){
                    ewidth += this.offsetWidth;
                });
                edl.find('.wysiwyg-table').width(ewidth)

                edl.css({
                    maxWidth: 'calc(100% - '
                        + ((edl.offset().left + mw.$('#mw-toolbar-right').width()) + 20 )
                        + 'px)'
                });
                mw.liveedit.toolbar.editor.buttons();

            });

            $(".wysiwyg-table").draggable({
                axis: 'x',
                drag : function(event,ui){
                    var parent = ui.helper[0].parentNode;

                    var dragWidth = ui.helper[0].clientWidth;
                    var parentWidth = parent.clientWidth;

                    var widthDifference = dragWidth - parentWidth;


                    if(ui.position.left > 0) ui.position.left = 0;
                    else if(ui.position.left < -widthDifference) ui.position.left = -widthDifference;



                }
            });




            mw.$(".show_editor").click(function () {
                mw.$("#liveedit_wysiwyg").toggle();
                mw.liveedit.toolbar.editor.buttons();
                $(this).toggleClass("active");
            });


            mw.$(".create-content-dropdown").hover(function () {
                if (typeof mw.wysiwyg.hide_drag_handles == 'function') {
                    mw.wysiwyg.hide_drag_handles();
                }
                var el = $(this);

                if (typeof(el[0]) == 'undefined') {
                    return;
                }
                el.addClass("over");
            }, function () {
                var el = $(this);
                el.removeClass("over");
                setTimeout(function () {
                    if (!el.hasClass("over")) {
                        if (typeof mw.wysiwyg.show_drag_handles == 'function') {
                            mw.wysiwyg.show_drag_handles();
                        }
                    }
                }, 322);
            });

            $(document.querySelectorAll('.edit')).each(function () {
                mw.linkTip.init(this);
            })
        });
    </script>
    <?php event_trigger('live_edit_toolbar_end'); ?>
    <?php include mw_includes_path() . 'toolbar' . DS . "design.php"; ?>
<?php } else { ?>
    <script>
        previewHTML = function (html, index) {
            mw.$('.edit').eq(index).html(html);
        }
        window.onload = function () {
            if (window.opener !== null) {
                window.opener.mw.$('.edit').each(function (i) {
                    var html = $(this).html();
                    self.previewHTML(html, i);
                });
            }
        }
    </script>
    <style>
        .delete_column {
            display: none;
        }
    </style>
<?php } ?>

<script>
    mw.require("plus.js");
    mw.require("columns.js");

    $(window).load(function () {

        <?php if (!isset($_COOKIE['mw_basic_mode']) or $_COOKIE['mw_basic_mode'] != '1'): ?>
        mw.drag.plus.init('.edit');
        <?php endif; ?>
        mw.drag.columns.init();
    });
</script>

<span class="mw-plus-top mw-wyswyg-plus-element tip" data-tip="Insert module"></span>
<span class="mw-plus-bottom mw-wyswyg-plus-element tip" data-tip="Insert module"></span>

<template style="display: none" id="plus-modules-list">
    <h3>Select module</h3>
    <div class="plus-modules-list" >
        <input type="text" class="mw-ui-searchfield" placeholder="<?php _e('Search') ?>"/>
        <div class="plus-modules-list-box">
            <module type="admin/modules/list" data-clean="true" class="modules-list-init module-as-element">
        </div>
    </div>
</template>

<template style="display: none" id="plus-layouts-list">
    <h3>Select layout</h3>
    <div class="plus-modules-list" >
        <input type="text" class="mw-ui-searchfield" placeholder="<?php _e('Search') ?>"/>
        <div class="plus-modules-list-box">
            <module type="admin/modules/list_layouts" data-clean="true" class="modules-list-init module-as-element">
        </div>
    </div>
</template>



