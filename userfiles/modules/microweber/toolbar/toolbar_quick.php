<?php if (!isset($_GET['preview'])){ ?>
<script type="text/javascript">
        mw.settings.liveEdit = true;
        mw.require("liveadmin.js");
        mw.lib.require("jqueryui");
        mw.require("events.js");
        mw.require("url.js");
        mw.require("wysiwyg.js");
        mw.require("css_parser.js");

        mw.require("forms.js");
        mw.require("files.js");
        mw.require("content.js", true);
        mw.require("session.js");
        mw.require("<?php print mw()->template->get_liveeditjs_url()  ?>");


    </script>


<script type="text/javascript">
        $(document).ready(function () {
          $(mwd.body).addClass("mw-admin-view")
        });
</script>





<link href="<?php print(mw_includes_url()); ?>css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php print(mw_includes_url()); ?>css/wysiwyg.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
        $(document).ready(function () {
            mw.tools.dropdown();
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
        <textarea class="mw-ui-field" placeholder='<?php _e('Enter Description'); ?>'
                              style="width: 505px; height: 35px;"></textarea>
      </div>
      <hr style="border-bottom: none">
      <span class='mw-ui-btn mw-ui-btn-green mw-ui-btn-saveIMG right'><?php _e('Update'); ?></span> </div>
  </div>
</div>
<?php include mw_includes_path() . 'toolbar' . DS . 'wysiwyg_tiny.php'; ?>
<script>
        mw.liveEditWYSIWYG = {
            ed: mwd.getElementById('liveedit_wysiwyg'),
            nextBTNS: mw.$(".liveedit_wysiwyg_next"),
            prevBTNS: mw.$(".liveedit_wysiwyg_prev"),
            step: function () {
                return  $(mw.liveedit.toolbar.editor.ed).width();
            },
            denied: false,
            buttons: function () {
                var b = mw.liveedit.toolbar.editor.calc.SliderButtonsNeeded(mw.liveedit.toolbar.editor.ed);
				if(b == null){
				return;
				}
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
            $(window).bind("resize", function () {
                mw.liveedit.toolbar.editor.buttons();

                var n = mw.liveedit.toolbar.editor.calc.SliderNormalize(mw.liveedit.toolbar.editor.ed);
                if (!!n) {
                    mw.liveedit.toolbar.editor.slideRight();
                }
            });
            mw.$(".tst-modules").click(function () {

                mw.$(this).next('ul').hide()
                var has_active = mwd.querySelector(".mw_toolbar_tab.active") !== null;
                if (!has_active) {
                    mw.tools.addClass(mwd.getElementById('tab_modules'), 'active');
                    mw.tools.addClass(mwd.querySelector('.mwtb-search-modules'), 'active');
                    $(mwd.querySelector('.mwtb-search-modules')).focus();
                }
                mw.liveedit.toolbar.fixPad();
            });


            var tab_modules = mwd.getElementById('tab_modules');
            var tab_layouts = mwd.getElementById('tab_layouts');
            var modules_switcher = mwd.getElementById('modules_switcher');
            // var modules_switch = mwd.getElementById('modules_switch');
			if(modules_switcher == null){
			return;
			}
            modules_switcher.searchIn = 'Modules_List_modules';


            $(modules_switcher).bind("keyup paste", function () {
                mw.tools.toolbar_searh(window[modules_switcher.searchIn], this.value);

            });




            mw.$(".show_editor").click(function () {
                mw.$("#liveedit_wysiwyg").toggle();
                mw.liveedit.toolbar.editor.buttons();
                $(this).toggleClass("active");
            });


            mw.$(".create-content-dropdown")
                .on('mouseenter touchstart', function () {
                var el = $(this);
                var list = mw.$(".create-content-dropdown-list", el[0]);

                el.addClass("over");
                setTimeout(function () {
                    mw.$(".create-content-dropdown-list").not(list).hide();
                    if (el.hasClass("over")) {
                        list.stop().show().css("opacity", 1);
                    }
                }, 222);
            }).on("mouseleave", function(){
                    var el = $(this);
                    el.removeClass("over");
                    setTimeout(function () {
                        if (!el.hasClass("over")) {
                            mw.$(".create-content-dropdown-list", el[0]).stop().fadeOut(322);
                        }
                    }, 322);
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
