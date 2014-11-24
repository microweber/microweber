<?php if (!isset($_GET['preview'])){ ?>
<script type="text/javascript">
        mw.settings.liveEdit = true;
        mw.require("liveadmin.js");
        mw.require("<?php print( mw_includes_url());  ?>js/jquery-ui-1.10.0.custom.min.js");
        mw.require("events.js");
        mw.require("url.js");
        mw.require("tools.js");
        mw.require("wysiwyg.js");
        mw.require("css_parser.js");
        mw.require("style_editors.js");
        mw.require("forms.js");
        mw.require("files.js");
        mw.require("content.js", true);
        mw.require("session.js");
        mw.require("liveedit.js");
  
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
          $(mwd.body).mousedown(function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-defaults')){
                mw.helpinfo.hide(true);
            }
          });
     });
  }

  $(window).bind('load', function(){
     <?php if(file_exists(TEMPLATE_DIR.'template_settings.php')){ ?>
        var show_settings = mw.cookie.get('remove_template_settings') != 'true';
        if(show_settings){
          mw.tools.template_settings(true);
        }
     <?php  } ?>
  });

</script>
<link href="<?php print(mw_includes_url()); ?>css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php print(mw_includes_url()); ?>css/wysiwyg.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
        $(document).ready(function () {
            mw.toolbar.minTop = parseFloat($(mwd.body).css("paddingTop"));
            setTimeout(function () {
                mw.history.init();
            }, 500);
            mw.tools.module_slider.init();
            mw.tools.dropdown();
            mw.tools.toolbar_slider.init();
            mw_save_draft_int = self.setInterval(function () {
                mw.drag.save(mwd.getElementById('main-save-btn'), false, true);
                if (mw.askusertostay) {
                    mw.tools.removeClass(mwd.getElementById('main-save-btn'), 'disabled');
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
      <p style="text-align: center"> <img src="<?php print INCLUDES_URL; ?>img/dropf.gif" alt="" /> </p>
      <p> You can easily grab any Module and insert it in your content. </p>
    </div>
  </div>
</div>
<div class="mw-defaults" id="live_edit_toolbar_holder">
  <div id="live_edit_toolbar">
    <div id="mw-text-editor" class="mw-defaults mw_editor">
      <div class="toolbar-sections-tabs">

        <ul>
          <li class="create-content-dropdown"> <a href="javascript:;" class="tst-logo" title="Microweber"> <span>Microweber</span> <i class=" mw-dropdown-arrow right"></i> </a>
            <div class="mw-dropdown-list create-content-dropdown-list"
                         style="box-shadow: 2px 2px 10px -10px #111;width: 225px;">
              <div class="mw-dropdown-list-search">
                <input type="mwautocomplete" class="mwtb-search mw-dropdown-search"
                                   placeholder="Search content"/>
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
                        mw('content')->pages_tree($pt_opts);
                        ?>
              <a id="backtoadminindropdown" href="<?php print $back_url; ?>" title="Back to Admin"> <span class="ico ibackarr"></span><span>Back to Admin</span> </a> </div>
          </li>
              <?php event_trigger('live_edit_toolbar_menu_start'); ?>
          <li class="create-content-dropdown mw-toolbar-btn-menu"> <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium" title="Create or manage your content" style=""> <span class="ico iplus"></span> Add New </a>
        
            <ul class="mw-dropdown-list create-content-dropdown-list liveeditcreatecontentmenu"
                        style="width: 170px; text-transform:uppercase;">
              <?php event_trigger('live_edit_quick_add_menu_start'); ?>
              <li><a href="javascript:;" onclick="mw.quick.edit(<?php print CONTENT_ID; ?>);"><span
                                    class="ico ieditpage"
                                    style="margin-right: 12px;"></span><span>
                <?php _e("Edit current"); ?>
                </span></a> </li>
              <li><a href="javascript:;" onclick="mw.quick.post();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico ipost"></span>
                <?php _e("Post"); ?>
                </a></li>
              <li><a href="javascript:;" onclick="mw.quick.page();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico ipage"></span>
                <?php _e("Page"); ?>
                </a></li>
              <li><a href="javascript:;" onclick="mw.quick.category();"><span
                                    class="mw-ui-btn-plus left"></span><span
                                    class="ico icategory"></span>
                <?php _e("Category"); ?>
                </a></li>
              <?php event_trigger('live_edit_quick_add_menu_end'); ?>
            </ul>
          </li>
          <?php /*    <li><a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium" title="Browse Modules" style="margin: 12px 12px 12px 0;background-color: #fff">Modules</a></li>  */ ?>
          <?php /*<li><a href="#design_bnav" class="tst-design mw_ex_tools" title="Design & Settings"><span>Design & Settings</span></a>
                </li>*/ ?>
          <li> <span style="display: none"
                        class="liveedit_wysiwyg_prev"
                        id="liveedit_wysiwyg_main_prev"
                        title="<?php _e("Previous"); ?>"
                        onclick="mw.liveEditWYSIWYG.slideLeft();"> </span> </li>
                        
         <?php event_trigger('live_edit_toolbar_menu_end'); ?>

        </ul>
        
    

        
        
      </div>
      <div id="mw-toolbar-right" class="mw-defaults"> <span class="liveedit_wysiwyg_next" id="liveedit_wysiwyg_main_next" title="<?php _e("Next"); ?>"
                          onclick="mw.liveEditWYSIWYG.slideRight();"></span>
                          
                          
                          

                          
                          
                          
                          
        <div class="right" style="padding: 5px 0;"> 
        
        
                <?php event_trigger('live_edit_toolbar_action_buttons'); ?>

        
        
        <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-ui-btn right"
                              onclick="mw.drag.save(this)" id="main-save-btn">
          <?php _e("Save"); ?>
          </span>
          <div class="toolbar-sections-tabs mw-ui-dropdown right" id="toolbar-dropdown-actions"> <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium" style="margin-left: 0;"><span
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
                <li><a href="<?php print mw('url')->current(); ?>?editmode=n"><span
                                        class="ico iviewsite"></span><span>
                  <?php _e("View Website"); ?>
                  </span></a> </li>
                  
                  
                 <?php event_trigger('live_edit_toolbar_action_menu_middle'); ?>
 
                  
                  
                  
                <?php /*<li><a href="#" onclick="mw.preview();void(0);"><span class="ico ibackarr"></span><span><?php _e("Preview"); ?></span></a></li>*/ ?>
                <?php if (defined('CONTENT_ID') and CONTENT_ID > 0): ?>
                <?php $pub_or_inpub = mw('content')->get_by_id(CONTENT_ID); ?>
                <li class="mw-set-content-unpublish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 'y'): ?> style="display:none" <?php endif; ?>> <a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><span
                                            class="ico iUnpublish"></span><span>
                  <?php _e("Unpublish"); ?>
                  </span></a> </li>
                <li class="mw-set-content-publish" <?php if (isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 'y'): ?> style="display:none" <?php endif; ?>> <a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><span
                                            class="ico iPublish"></span><span>
                  <?php _e("Publish"); ?>
                  </span></a> </li>
                <?php endif; ?>

                <li><a  href="#design_bnav" class="mw_ex_tools"><span class="ico itabadvanced"></span>Tools</a></li>
                <li><a href="<?php print mw('url')->api_link('logout'); ?>"><span
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
            If you want to edit this section you have to switch do "<strong>Advanced Mode</strong>".
            <div class="Switch2AdvancedModeTiphr"></div>
            <div style="text-align: center"> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-green" onclick="mw.setMode('advanced');">Switch</span> <span class="mw-ui-btn mw-ui-btn-small" onclick="$(this.parentNode.parentNode).hide();mw.doNotBindSwitcher=true;">Cancel</span> </div>
          </div>
        </div>
         
      </div>
      <?php include MW_INCLUDES_DIR . 'toolbar' . DS . 'wysiwyg.php'; ?>
    </div>
    <?php event_trigger('live_edit_toolbar_controls'); ?>

    <div id="modules-and-layouts" style="" class="modules-and-layouts-holder">
      <div class="toolbars-search">
        <div class="mw-autocomplete left">
          <input
                    type="mwautocomplete"
                    autocomplete="off"
                    id="modules_switcher"
                    data-for="modules"
                    class="mwtb-search mwtb-search-modules"
                    placeholder="<?php _e("Modules"); ?>"/>
          <div class="mw_clear"></div>
          <span class="mw-ui-btn mw-ui-btn-blue mw-ui-btn-small" id="mod_switch" data-action="layouts">
          <?php _e("Switch to Layouts"); ?>
          </span>
          <?php /*<button class="mw-ui-btn mw-ui-btn-medium" id="modules_switch">Layouts</button>*/ ?>
        </div>
      </div>
      <div id="tab_modules" class="mw_toolbar_tab active">
        <div class="modules_bar_slider bar_slider">
          <div class="modules_bar">
            <module type="admin/modules/list"/>
          </div>
          <span class="modules_bar_slide_left">&nbsp;</span> <span
                    class="modules_bar_slide_right">&nbsp;</span> </div>
        <div class="mw_clear">&nbsp;</div>
      </div>
      <div id="tab_layouts" class="mw_toolbar_tab">
        <div class="modules_bar_slider bar_slider">
          <div class="modules_bar">
            <module type="admin/modules/list_layouts"/>
          </div>
          <span class="modules_bar_slide_left">&nbsp;</span> <span
                    class="modules_bar_slide_right">&nbsp;</span> </div>
      </div>
    </div>
    <div id="mw-saving-loader"></div>
  </div>
</div>
<div id="image_settings_modal_holder" style="display: none">
  <div class='image_settings_modal'>
    <div class="mw-ui-box mw-ui-box-content">
      <hr style="border-bottom: none">
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Alignment</label>
        <span class="mw-img-align mw-img-align-left" title="Left" data-align="left"></span> <span class="mw-img-align mw-img-align-center" title="Center" data-align="center"></span> <span class="mw-img-align mw-img-align-right" title="Right" data-align="right"></span> </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Effects</label>
        <div class="mw-ui-btn-nav"> <span title="Vintage Effect"
                              onclick="mw.image.vintage(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"
                              class="mw-ui-btn">Vintage Effect</span> <span title="Convert to Grayscale"
                              onclick="mw.image.grayscale(mw.image.current);mw.$('#mw_image_reset').removeClass('disabled')"
                              class="mw-ui-btn">Convert to Grayscale</span> <span class="mw-ui-btn"
                              onclick="mw.image.rotate(mw.image.current);mw.image.current_need_resize = true;mw.$('#mw_image_reset').removeClass('disabled')">Rotate</span> <span class="mw-ui-btn disabled" id="mw_image_reset">Reset</span> </div>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Image Description</label>
        <textarea class="mw-ui-field" placeholder='Enter Description'
                              style="width: 505px; height: 35px;"></textarea>
      </div>
      <hr style="border-bottom: none">
      <span class='mw-ui-btn mw-ui-btn-green mw-ui-btn-saveIMG right'>Update</span> </div>
  </div>
</div>
<?php include MW_INCLUDES_DIR . 'toolbar' . DS . 'wysiwyg_tiny.php'; ?>
<script>
        mw.liveEditWYSIWYG = {
            ed: mwd.getElementById('liveedit_wysiwyg'),
            nextBTNS: mw.$(".liveedit_wysiwyg_next"),
            prevBTNS: mw.$(".liveedit_wysiwyg_prev"),
            step: function () {
                return  $(mw.liveEditWYSIWYG.ed).width();
            },
            denied: false,
            buttons: function () {
                var b = mw.tools.calc.SliderButtonsNeeded(mw.liveEditWYSIWYG.ed);
                if (b.left) {
                    mw.liveEditWYSIWYG.prevBTNS.show();
                }
                else {
                    mw.liveEditWYSIWYG.prevBTNS.hide();
                }
                if (b.right) {
                    mw.liveEditWYSIWYG.nextBTNS.show();
                }
                else {
                    mw.liveEditWYSIWYG.nextBTNS.hide();
                }
            },
            slideLeft: function () {
                if (!mw.liveEditWYSIWYG.denied) {
                    mw.liveEditWYSIWYG.denied = true;
                    var el = mw.liveEditWYSIWYG.ed.firstElementChild;
                    var to = mw.tools.calc.SliderPrev(mw.liveEditWYSIWYG.ed, mw.liveEditWYSIWYG.step());
                    $(el).animate({left: to}, function () {
                        mw.liveEditWYSIWYG.denied = false;
                        mw.liveEditWYSIWYG.buttons();
                    });
                }
            },
            slideRight: function () {
                if (!mw.liveEditWYSIWYG.denied) {
                    mw.liveEditWYSIWYG.denied = true;
                    var el = mw.liveEditWYSIWYG.ed.firstElementChild;

                    var to = mw.tools.calc.SliderNext(mw.liveEditWYSIWYG.ed, mw.liveEditWYSIWYG.step());
                    $(el).animate({left: to}, function () {
                        mw.liveEditWYSIWYG.denied = false;
                        mw.liveEditWYSIWYG.buttons();
                    });
                }
            },
            fixConvertible: function (who) {
                var who = who || ".wysiwyg-convertible";
                var who = $(who);
                if (who.length > 1) {
                    $(who).each(function () {
                        mw.liveEditWYSIWYG.fixConvertible(this);
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
            mw.liveEditWYSIWYG.buttons();
            $(window).bind("resize", function () {
                mw.liveEditWYSIWYG.buttons();

                var n = mw.tools.calc.SliderNormalize(mw.liveEditWYSIWYG.ed);
                if (!!n) {
                    mw.liveEditWYSIWYG.slideRight();
                }
            });
            mw.$(".tst-modules").click(function () {
                mw.$('#modules-and-layouts').toggleClass("active");
                mw.$(this).next('ul').hide()
                var has_active = mwd.querySelector(".mw_toolbar_tab.active") !== null;
                if (!has_active) {
                    mw.tools.addClass(mwd.getElementById('tab_modules'), 'active');
                    mw.tools.addClass(mwd.querySelector('.mwtb-search-modules'), 'active');
                    $(mwd.querySelector('.mwtb-search-modules')).focus();
                }
                mw.toolbar.fixPad();
            });


            var tab_modules = mwd.getElementById('tab_modules');
            var tab_layouts = mwd.getElementById('tab_layouts');
            var modules_switcher = mwd.getElementById('modules_switcher');
            // var modules_switch = mwd.getElementById('modules_switch');

            modules_switcher.searchIn = 'Modules_List_modules';


            $(modules_switcher).bind("keyup paste", function () {
                mw.tools.toolbar_searh(window[modules_switcher.searchIn], this.value);

            });


            mw.$(".toolbars-search").hover(function () {
                mw.tools.addClass(this, 'hover');
            }, function () {
                mw.tools.removeClass(this, 'hover');
            });

            mw.$(".show_editor").click(function () {
                mw.$("#liveedit_wysiwyg").toggle();
                mw.liveEditWYSIWYG.buttons();
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


            mw.$("#mod_switch").click(function () {
                var h = $(this).dataset("action");
                toolbar_set(h);
                if (h == 'layouts') {
                    this.innerHTML = '<?php _e("Switch to Modules"); ?>';
                    $(this).dataset("action", 'modules');
                }
                else {
                    this.innerHTML = '<?php _e("Switch to Layouts"); ?>';
                    $(this).dataset("action", 'layouts');
                }
            });


        });


        toolbar_set = function (a) {
            mw.$("#modules-and-layouts, .tst-modules").addClass("active");
            modules_switcher.value = '';
            mw.$("#modules-and-layouts .module-item").show();

            mw.$(".modules_bar_slide_left").hide();

            mw.$(".modules_bar").scrollLeft(0);

            mw.cookie.ui("#modules-and-layouts,#tab_modules,.tst-modules", "true");
            mw.$(".modules-layouts-menu .create-content-dropdown-list").hide();

            if (a == 'layouts') {
                mw.$(modules_switcher).dataset("for", "layouts");
                mw.$(modules_switcher).attr("placeholder", "Layouts");
                // $(modules_switch).html("Modules");
                $(modules_switcher).focus();
                modules_switcher.searchIn = 'Modules_List_elements';
                mw.tools.addClass(tab_layouts, 'active');
                mw.tools.removeClass(tab_modules, 'active');
            }
            else if (a == 'modules') {
                mw.$(modules_switcher).dataset("for", "modules");
                mw.$(modules_switcher).attr("placeholder", "Modules");
                //   $(modules_switch).html("Layouts");
                $(modules_switcher).focus();
                modules_switcher.searchIn = 'Modules_List_modules';
                mw.tools.addClass(tab_modules, 'active');
                mw.tools.removeClass(tab_layouts, 'active');
            }
        }


    </script>
<?php event_trigger('live_edit_toolbar_end'); ?>
<?php include MW_INCLUDES_DIR . 'toolbar' . DS . "design.php"; ?>
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
