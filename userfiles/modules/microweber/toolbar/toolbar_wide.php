<?php if (!isset($_GET['preview'])){ ?>
<script type="text/javascript">
        mw.settings.liveEdit = true;
        mw.require("liveadmin.js");
        mw.lib.require("jqueryui");
        mw.require("events.js");
        mw.require("url.js");
        //mw.require("tools.js");
        mw.require("wysiwyg.js");
        mw.require("css_parser.js");

        mw.require("forms.js");
        mw.require("files.js");
        mw.require("content.js", true);
        mw.require("session.js");
        mw.require("<?php print mw()->template->get_liveeditjs_url()  ?>");

    </script>
<script type="text/javascript">
if(mw.cookie.get("helpinfoliveedit") != 'false'){
     mw.require("helpinfo.js", true);
     mw.require("<?php print mw_includes_url(); ?>css/helpinfo.css", true);
}
</script>
<script type="text/javascript">


  if(mw.cookie.get("helpinfoliveedit") != 'false'){
     mw.helpinfo.cookie = "helpinfoliveedit";
     mw.helpinfo.pauseInit = true;
     $(window).bind("load", function(){
        mw.mouse.gotoAndClick("#modules-and-layouts",
          {
              left:mw.$("#modules-and-layouts").width()/2,
              top:0
          });
          setTimeout(function(){
              mw.tools.scrollTo();
              mw.helpinfo.init();
              setTimeout(function(){
                mw.helpinfo.hide(true);
              }, 8000);
              mw.$("#mw_info_helper_footer .mw-ui-btn").eq(0).bind("click", function(){
                mw.helpinfo.hide(true);
              });
          }, 2000);
          $(document.body).mousedown(function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-defaults')){
                mw.helpinfo.hide(true);
            }
          });
     });
  }



</script>
<link href="<?php print(mw_includes_url()); ?>css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php print(mw_includes_url()); ?>css/wysiwyg.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
        $(document).ready(function () {

            mw.tools.dropdown();
            mw_save_draft_int = self.setInterval(function () {
                mw.drag.save(document.getElementById('main-save-btn'), false, true);
                if (mw.askusertostay) {
                    mw.tools.removeClass(document.getElementById('main-save-btn'), 'disabled');
                }
            }, 1000);
        });
    </script>
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

<div class="mw-helpinfo semi_hidden">
  <div class="mw-help-item" data-for="#live_edit_toolbar" data-pos="bottomcenter">
    <div style="width: 300px;">
      <p style="text-align: center"> <img src="<?php print mw_includes_url(); ?>img/dropf.gif" alt="" /> </p>
      <p> <?php _e('You can easily grab any Module and insert it in your content.'); ?> </p>
    </div>
  </div>
</div>
<div class="mw-defaults" id="live_edit_toolbar_holder">
  <div id="live_edit_toolbar">
    <div id="mw-text-editor" class="mw-defaults mw_editor">
      <div class="toolbar-sections-tabs">

        <ul>
          <li class="create-content-dropdown">
              <a href="javascript:;" class="tst-logo" title="Microweber"> <span>Microweber</span> <i class=" mw-dropdown-arrow right"></i> </a>
            <div class="mw-dropdown-list create-content-dropdown-list" style="box-shadow: 2px 2px 10px -10px #111;width: 225px;">
              <div class="mw-dropdown-list-search">
                <input type="mwautocomplete" class="mwtb-search mw-dropdown-search" placeholder="Search content"/>
              </div>
              <?php
                        $pt_opts = array();
                        $pt_opts['link'] = "<a href='{link}#tab=pages'>{title}</a>";
                        $pt_opts['list_tag'] = "ul";
                        $pt_opts['ul_class'] = "";
                        $pt_opts['list_item_tag'] = "li";
                        $pt_opts['active_ids'] = CONTENT_ID;
                        $pt_opts['limit'] = 1000;
                        $pt_opts['active_code_tag'] = 'class="active"';
                        mw()->content_manager->pages_tree($pt_opts);
                        ?>
              <a id="backtoadminindropdown" href="<?php print $back_url; ?>" title="Back to Admin"> <span class="ico ibackarr"></span><span><?php _e('Back to Admin'); ?></span> </a> </div>
          </li>
              <?php event_trigger('live_edit_toolbar_menu_start'); ?>
          <li class="create-content-dropdown mw-toolbar-btn-menu"> <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium" title="Create or manage your content" style=""> <span class="ico iplus"></span> <?php _e('Add New'); ?> </a>

            <ul class="mw-dropdown-list create-content-dropdown-list liveeditcreatecontentmenu"
                        style="width: 170px; text-transform:uppercase;">
              <?php event_trigger('live_edit_quick_add_menu_start'); ?>
              <li><a href="javascript:;" onclick="mw.liveedit.manageContent.edit(<?php print CONTENT_ID; ?>);"><span
                                    class="ico ieditpage"
                                    style="margin-inline-end: 12px;"></span><span>
                <?php _e("Edit current"); ?>
                </span></a> </li>
              <li><a href="javascript:;" onclick="mw.liveedit.manageContent.post();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico ipost"></span>
                <?php _e("Post"); ?>
                </a></li>
              <li><a href="javascript:;" onclick="mw.liveedit.manageContent.page();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico ipage"></span>
                <?php _e("Page"); ?>
                </a></li>
              <li><a href="javascript:;" onclick="mw.liveedit.manageContent.category();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico icategory"></span>
                <?php _e("Category"); ?>
                </a></li>
              <?php event_trigger('live_edit_quick_add_menu_end'); ?>
            </ul>
          </li>

          <li> <span style="display: none"
                        class="liveedit_wysiwyg_prev"
                        id="liveedit_wysiwyg_main_prev"
                        title="<?php _e("Previous"); ?>"
                        onclick="mw.liveedit.toolbar.editor.slideLeft();"> </span> </li>

         <?php event_trigger('live_edit_toolbar_menu_end'); ?>

        </ul>





      </div>
      <div id="mw-toolbar-right" class="mw-defaults"> <span class="liveedit_wysiwyg_next" id="liveedit_wysiwyg_main_next" title="<?php _e("Next"); ?>"
                          onclick="mw.liveedit.toolbar.editor.slideRight();"></span>








        <div class="right" style="padding: 5px 0;">


                <?php event_trigger('live_edit_toolbar_action_buttons'); ?>



        <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-ui-btn right"
                              onclick="mw.drag.save(this)" id="main-save-btn">
          <?php _e("Save"); ?>
          </span>
          <div class="toolbar-sections-tabs mw-ui-dropdown right" id="toolbar-dropdown-actions"> <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium" style="margin-inline-start: 0;"><span
                            class="mw-dropdown-arrow right"></span>
            <?php _e("Actions"); ?>
            </a>
            <div class="mw-dropdown-content" style="width: 155px;">
              <ul class="mw-dropdown-list mw-dropdown-list-icons">


               <?php event_trigger('live_edit_toolbar_action_menu_start'); ?>



                <li> <a title="Back to Admin" href="<?php print $back_url; ?>"><span
                                        class="ico ibackarr"></span><span>
                  <?php _e("Back to Admin"); ?>
                  </span></a> </li>
                <li>
                  <script>

                                        mw.userCanSwitchMode = false;

                                    </script>
                  <?php if (!isset($user['basic_mode']) or $user['basic_mode'] != 'y') { ?>
                  <script>

                                        mw.userCanSwitchMode = true;

                                    </script>
                  <?php if (isset($_COOKIE['advancedmode']) and $_COOKIE['advancedmode'] == 'true') { ?>
                  <a href="javascript:;" onclick="mw.setMode('simple');" style="display:none">Simple Mode</a>
                  <?php } else { ?>
                  <a href="javascript:;" onclick="mw.setMode('advanced')" style="display:none">Advanced Mode</a>
                  <?php } ?>
                  <?php }  ?>
                </li>
                <li><a href="<?php print mw()->url_manager->current(); ?>?editmode=n"><span
                                        class="ico iviewsite"></span><span>
                  <?php _e("View Website"); ?>
                  </span></a> </li>


                 <?php event_trigger('live_edit_toolbar_action_menu_middle'); ?>




                <?php if (defined('CONTENT_ID') and CONTENT_ID > 0): ?>
                <?php $pub_or_inpub = mw()->content_manager->get_by_id(CONTENT_ID); ?>
                <li class="mw-set-content-unpublish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 'y'): ?> style="display:none" <?php endif; ?>> <a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><span
                                            class="ico iUnpublish"></span><span>
                  <?php _e("Unpublish"); ?>
                  </span></a> </li>
                <li class="mw-set-content-publish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 'y'): ?> style="display:none" <?php endif; ?>> <a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><span
                                            class="ico iPublish"></span><span>
                  <?php _e("Publish"); ?>
                  </span></a> </li>
                <?php endif; ?>

                <li><a href="<?php print mw()->url_manager->logout_url(); ?>"><span
                                        class="ico ilogout"></span><span>
                  <?php _e("Logout"); ?>
                  </span></a></li>


                 <?php event_trigger('live_edit_toolbar_action_menu_end'); ?>



              </ul>
            </div>
          </div>
          <div class="Switch2AdvancedModeTip" style="display: none">
            <div class="Switch2AdvancedModeTip-tickContainer">
              <div class="Switch2AdvancedModeTip-tick"></div>
              <div class="Switch2AdvancedModeTip-tick2"></div>
            </div>
              <?php _e('If you want to edit this section you have to switch do'); ?> "<strong><?php _e('Advanced Mode'); ?></strong>".
            <div class="Switch2AdvancedModeTiphr"></div>
            <div style="text-align: center"> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-green" onclick="mw.setMode('advanced');"><?php _e('Switch'); ?></span> <span class="mw-ui-btn mw-ui-btn-small" onclick="$(this.parentNode.parentNode).hide();mw.doNotBindSwitcher=true;"><?php _e('Cancel'); ?></span> </div>
          </div>
        </div>

      </div>
      <?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg.php'; ?>
    </div>
    <?php event_trigger('live_edit_toolbar_controls'); ?>


    <div id="mw-saving-loader"></div>
  </div>
</div>
<div id="image_settings_modal_holder" style="display: none">
  <div class='image_settings_modal'>
    <div class="mw-ui-box mw-ui-box-content">
      <hr style="border-bottom: none">
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Alignment'); ?></label>
        <span class="mw-img-align mw-img-align-left" title="Left" data-align="left"></span> <span class="mw-img-align mw-img-align-center" title="Center" data-align="center"></span> <span class="mw-img-align mw-img-align-right" title="Right" data-align="right"></span> </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Effects'); ?></label>
        <div class="mw-ui-btn-nav"> <span title="Vintage Effect"
                              onclick="mw.image.vintage(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"
                              class="mw-ui-btn"><?php _e('Vintage Effect'); ?></span> <span title="Convert to Grayscale"
                              onclick="mw.image.grayscale(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"
                              class="mw-ui-btn"><?php _e('Convert to Grayscale'); ?></span> <span class="mw-ui-btn"
                              onclick="mw.image.rotate(mw.image.current);mw.image.current_need_resize = true;mw.$('#mw_image_reset').removeClass('disabled')"><?php _e('Rotate'); ?></span> <span class="mw-ui-btn disabled" id="mw_image_reset"><?php _e('Reset'); ?></span> </div>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Image Description'); ?></label>
        <textarea class="mw-ui-field" placeholder='Enter Description'
                              style="width: 505px; height: 35px;"></textarea>
      </div>
      <hr style="border-bottom: none">
      <span class='mw-ui-btn mw-ui-btn-green mw-ui-btn-saveIMG right'><?php _e('Update'); ?></span> </div>
  </div>
</div>
<?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg_tiny.php'; ?>
<script>
        mw.liveEditWYSIWYG = {
            ed: document.getElementById('liveedit_wysiwyg'),
            nextBTNS: mw.$(".liveedit_wysiwyg_next"),
            prevBTNS: mw.$(".liveedit_wysiwyg_prev"),
            step: function () {
                return  $(mw.liveedit.toolbar.editor.ed).width();
            },
            denied: false,
            buttons: function () {
                var b = mw.liveedit.toolbar.editor.calc.SliderButtonsNeeded(mw.liveedit.toolbar.editor.ed);
                if (b.left) {
                    mw.liveedit.toolbar.editor.prevBTNS.show();
                }
                else {
                    mw.liveedit.toolbar.editor.prevBTNS.hide();
                }
                if (b.right) {
                    mw.liveedit.toolbar.editor.nextBTNS.show();
                }
                else {
                    mw.liveedit.toolbar.editor.nextBTNS.hide();
                }
            },
            slideLeft: function () {
                if (!mw.liveedit.toolbar.editor.denied) {
                    mw.liveedit.toolbar.editor.denied = true;
                    var el = mw.liveedit.toolbar.editor.ed.firstElementChild;
                    var to = mw.liveedit.toolbar.editor.calc.SliderPrev(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                    $(el).animate({left: to}, function () {
                        mw.liveedit.toolbar.editor.denied = false;
                        mw.liveedit.toolbar.editor.buttons();
                    });
                }
            },
            slideRight: function () {
                if (!mw.liveedit.toolbar.editor.denied) {
                    mw.liveedit.toolbar.editor.denied = true;
                    var el = mw.liveedit.toolbar.editor.ed.firstElementChild;

                    var to = mw.liveedit.toolbar.editor.calc.SliderNext(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                    $(el).animate({left: to}, function () {
                        mw.liveedit.toolbar.editor.denied = false;
                        mw.liveedit.toolbar.editor.buttons();
                    });
                }
            },
            fixConvertible: function (who) {
                var who = who || ".wysiwyg-convertible";
                var who = $(who);
                if (who.length > 1) {
                    $(who).each(function () {
                        mw.liveedit.toolbar.editor.fixConvertible(this);
                    });
                    return false;
                }
                else {
                    var w = $(window).width();
                    var w1 = who.offset().left + who.width();
                    if (w1 > w) {
                        who.css("left", -(w1 - w));
                    }
                    else {
                        who.css("left", 0);
                    }
                }
            }
        }
        $(window).load(function () {
            mw.liveedit.toolbar.editor.buttons();
            $(window).on("resize", function () {
                mw.liveedit.toolbar.editor.buttons();

                var n = mw.liveedit.toolbar.editor.calc.SliderNormalize(mw.liveedit.toolbar.editor.ed);
                if (!!n) {
                    mw.liveedit.toolbar.editor.slideRight();
                }
            });
            mw.$(".tst-modules").click(function () {

                mw.$(this).next('ul').hide()
                var has_active = document.querySelector(".mw_toolbar_tab.active") !== null;
                if (!has_active) {
                    mw.tools.addClass(document.getElementById('tab_modules'), 'active');
                    mw.tools.addClass(document.querySelector('.mwtb-search-modules'), 'active');
                    $(document.querySelector('.mwtb-search-modules')).focus();
                }
                mw.liveedit.toolbar.fixPad();
            });


            var tab_modules = document.getElementById('tab_modules');
            var tab_layouts = document.getElementById('tab_layouts');
            var modules_switcher = document.getElementById('modules_switcher');
            // var modules_switch = document.getElementById('modules_switch');

            modules_switcher.searchIn = 'Modules_List_modules';


            $(modules_switcher).bind("keyup paste", function () {
                mw.tools.toolbar_searh(window[modules_switcher.searchIn], this.value);

            });



            mw.$(".show_editor").click(function () {
                mw.$("#liveedit_wysiwyg").toggle();
                mw.liveedit.toolbar.editor.buttons();
                $(this).toggleClass("active");
            });


            mw.$(".create-content-dropdown").hover(function () {
                var el = $(this);
                var list = mw.$(".create-content-dropdown-list", el[0]);

                el.addClass("over");
                setTimeout(function () {
                    mw.$(".create-content-dropdown-list").not(list).hide();
                    if (el.hasClass("over")) {
                        list.stop().show().css("opacity", 1);
                    }
                }, 222);

            }, function () {
                var el = $(this);
                el.removeClass("over");
                setTimeout(function () {
                    if (!el.hasClass("over")) {
                        mw.$(".create-content-dropdown-list", el[0]).stop().fadeOut(322);
                    }
                }, 322);
            });

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
