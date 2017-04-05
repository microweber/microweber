<?php if(!isset($_GET['preview'])){ ?>

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
    mw.require("<?php   print( mw_includes_url());  ?>js/sortable.js");
    mw.require("<?php   print( mw_includes_url());  ?>js/toolbar.js");
</script>
<link href="<?php   print( mw_includes_url());  ?>css/components.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( mw_includes_url());  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">



        $(document).ready(function () {
            mw.toolbar.max = 170;

            document.body.style.paddingTop = mw.toolbar.max + 'px';


            setTimeout(function(){
                mw.history.init();
            }, 500);
            mw.tools.module_slider.init();
            mw.tools.dropdown();
            mw.tools.toolbar_slider.init();
            $(".mw-ui-dropdown ul .mw-ui-btn").click(function(){
               var text = $(this).text();
               mw.$(".mw-ui-dropdown .mw-ui-btn .left").html(text);
               var first = mw.$(".mw-ui-dropdown .mw-ui-btn:first")[0];
               var span = mwd.createElement('span');
               span.innerHTML = $(first).find(".left").html();

               mw.tools.copyAttributes(first,span);

               mw.tools.copyAttributes(this,first);

              $(this).parents("ul").invisible();
                  var el = $(this).parents("ul");
                  $(this).replaceWith(span)
                setTimeout(function(){
                    el.visibilityDefault();

                }, 177);
            });

            mw_save_draft_int = self.setInterval(function(){
               mw.drag.save(mwd.getElementById('main-save-btn'), false, true);
            },1000);
        });








    </script>
<?php


			 $back_url = site_url().'admin/view:content';
			 if(defined('CONTENT_ID')){
				 $back_url .= '#action=editpage:'.CONTENT_ID;
			 }

			  if(isset($_COOKIE['back_to_admin'])){

				 $back_url = $_COOKIE['back_to_admin'];

			  }



			  ?>
<span id="show_hide_sub_panel" onclick="mw.toggle_subpanel();"><span id="show_hide_sub_panel_slider"></span><span id="show_hide_sub_panel_info" style="left: auto"><?php _e("Less"); ?></span></span>


<div id="mw-toolbar-right" class="mw-defaults">


<div class="mw-ui-dropdown right" id="history_dd">
          <a class="mw-ui-btn mw-ui-btn-hover mw-btn-single-ico" onclick="mw.$('#historycontainer').toggle();" title="<?php _e("Drafts"); ?>"><span class="ico ihistory" style="height: 22px;"></span></a>
          <div class="mw-dropdown-content" style="width: 150px;right: -50px;left: auto;display: none;visibility: visible" id="historycontainer">
            <ul class="mw-dropdown-list">
                <li>
                    <div id="mw-history-panel"></div>
                </li>
            </ul>
          </div>
        </div>

      <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-ui-btn right" onclick="mw.drag.save(this)" id="main-save-btn"><?php _e("Save"); ?></span>






        <?php /*<a href="javascript:;" class="mw-ui-btn" onclick="mw.iphonePreview();"><span class="ico iPhone"></span>iPhone</a>*/   ?>
        <div class="mw-ui-dropdown right"> <a href="<?php print mw()->url_manager->current(); ?>/editmode:n" class="mw-ui-btn mw-ui-btn-medium" style="margin-left: 0;"><?php _e("Actions"); ?><span class="ico idownarr right"></span></a>
          <div class="mw-dropdown-content" style="width: 155px;">

            <ul class="mw-dropdown-list">

            <li>
                <a title="Back to Admin" class="mw-ui-btn-blue back_to_admin" href="<?php print $back_url; ?>"><?php _e("Back to Admin"); ?></a>
                <div class="mw_clear"></div>
            </li>
              <li><a href="<?php print mw()->url_manager->current(); ?>?editmode=n"><?php _e("View Website"); ?></a></li>


              <li><a href="#" onclick="mw.preview();void(0);"><?php _e("Preview"); ?></a></li>
              <?php if(defined('CONTENT_ID') and CONTENT_ID > 0): ?>
              <?php $pub_or_inpub  = mw()->content_manager->get_by_id(CONTENT_ID); ?>
              <li class="mw-set-content-unpublish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><?php _e("Unpublish"); ?></a></li>
              <li class="mw-set-content-publish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><?php _e("Publish"); ?></a></li>
              <?php endif; ?>
              <li><a href="<?php print mw()->url_manager->api_link('logout'); ?>"><?php _e("Logout"); ?></a></li>
            </ul>
          </div>
        </div>






      </div>



<div class="mw-defaults" id="live_edit_toolbar_holder">
  <div  id="live_edit_toolbar">

  <?php if(!$basic_mode): ?>

    <div id="mw_toolbar_nav"> <a href="#tab=modules" id="mw_toolbar_logo"><?php _e("Microweber - Live Edit"); ?></a>
      <?php /* <a href="javascript:;" style="position: absolute;top: 10px;right: 10px;" onclick="mw.extras.fullscreen(document.body);">Fullscreen</a> */  ?>
      <ul id="mw_tabs">
        <li id="t_modules"> <a href="#tab=modules" onclick="mw.url.windowHashParam('tab', 'modules');return false;">
          <?php _e('Modules'); ?>
          </a> </li>
        <li id="t_layouts"> <a href="#tab=layouts" onclick="mw.url.windowHashParam('tab', 'layouts');return false;">
          <?php _e('Layouts'); ?>
          </a> </li>
        <li id="t_pages"> <a href="#tab=pages" onclick="mw.url.windowHashParam('tab', 'pages');return false;">

      <span class="mw-ui-btn-plus"></span> <?php _e('Add New'); ?>
          <?php //_e('Pages'); ?>
          </a> </li>

      </ul>
      <div class="mw-ui-dropdown media-small" id="mw_tabs_small">
        <span class="mw-ui-btn"><span class="ico icomobilemenu"></span><span id="mw_small_menu_text"><?php _e("Menu"); ?></span><span class="ico idownarr right"></span></span>
        <div class="mw-dropdown-content">
          <ul class="mw-dropdown-list">
            <li id="t_modules"> <a href="#tab=modules" onclick="mw.url.windowHashParam('tab', 'modules');return false;">
              <?php _e('Modules');?>
              </a> </li>
            <li id="t_layouts"> <a href="#tab=layouts" onclick="mw.url.windowHashParam('tab', 'layouts');return false;">
              <?php _e('Layouts'); ?>
              </a> </li>
            <li id="t_pages"> <a href="#tab=pages" onclick="mw.url.windowHashParam('tab', 'pages');return false;">
              <?php _e('Pages'); ?>
              </a> </li>
            <li id="t_help"> <a href="#tab=help" onclick="mw.url.windowHashParam('tab', 'help');return false;">
              <?php _e('Help'); ?>
              </a> </li>
          </ul>
        </div>
      </div>
      <?php /*<a href="#design_bnav" class="mw-ui-btn mw-ui-btn-revert ed_btn mw_ex_tools" style="margin: 11px 0 0 12px; "><span></span>Design</a>*/ ?>

    </div>
    <div id="tab_modules" class="mw_toolbar_tab">


       <microweber module="admin/modules/categories_dropdown" no_wrap="true" template="liveedit_toolbar" />
      <div class ="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
      <div class="mw_clear">&nbsp;</div>




    </div>
    <div id="tab_layouts" class="mw_toolbar_tab">
      <microweber module="admin/modules/categories_dropdown" no_wrap="true" data-for="elements"  template="liveedit_toolbar" />
      <div class="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list_layouts" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
    </div>
    <div id="tab_pages" class="mw_toolbar_tab">




    <script>





    mw.quick = {
          w : 700,
          h : 500,
          page : function(){
           mw.tools.modal.frame({
              url:mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=true&id=mw-quick-page",
              template:'mw_modal_simple',
              width:mw.quick.w,
              height:mw.quick.h,
              name:'quick_page',
              title:'New Page'
           });
        },
		
		 page_2 : function(){
           mw.tools.modal.frame({
              url:mw.settings.api_url + "module/?type=content/quick_add&live_edit=true&id=mw-new-content-add-ifame",
              template:'mw_modal_simple',
              width:mw.quick.w,
              height:mw.quick.h,
              name:'quick_page',
              title:'New Page'
           });
        },
		
		
        post : function(){
            mw.tools.modal.frame({
              url:mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=true&id=mw-quick-post&subtype=post",
              template:'mw_modal_simple',
              width:mw.quick.w,
              height:mw.quick.h,
              name:'quick_post',
              title:'New Post'
            });
        },
        product : function(){
           mw.tools.modal.frame({
              url:mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=true&id=mw-quick-product&subtype=product",
              template:'mw_modal_simple',
              width:mw.quick.w,
              height:mw.quick.h,
              name:'quick_product',
              title:'New Product'
           });
        }
    }



    </script>

       <div id="liveedit_add_new_content">


         <a href="javascript:;" onclick="mw.quick.page();" class="mw-ui-btn "><span class="mw-ui-btn-plus left"></span><span class="ico ipage"></span> <?php _e('Add Page'); ?></a>
         <a href="javascript:;" onclick="mw.quick.post();" class="mw-ui-btn "><span class="mw-ui-btn-plus left"></span><span class="ico ipost"></span> <?php _e('Add Post'); ?></a>
         <a href="javascript:;" onclick="mw.quick.product();" class="mw-ui-btn "><span class="mw-ui-btn-plus left"></span><span class="ico iproduct"></span> <?php _e('Add Product'); ?></a>
		 

					<div class="mw-ui-dropdown mw-quick-pages-nav" style="z-index: 17;">
                        <a style="margin-left: 0;" class="mw-ui-btn mw-ui-btn-blue " href="javascript:;"><?php _e('Browse pages'); ?><span class="ico idownarr right"></span></a>
                       <div class="mw-dropdown-content" style="width: 200px;height: 200px;overflow: auto">
    					<?php
                            $pt_opts = array();
                            $pt_opts['link'] = "<a href='{link}#tab=pages'>{title}</a>";
                            $pt_opts['list_tag'] = "ul";
                            $pt_opts['ul_class'] = "mw-dropdown-list";
                            $pt_opts['list_item_tag'] = "li";
                            $pt_opts['active_ids'] = CONTENT_ID;
                            $pt_opts['limit'] = 1000;
                            $pt_opts['active_code_tag'] = '   class="active"  ';
                            mw()->content_manager->pages_tree($pt_opts);
                      ?></div>

					</div>
       </div>
    </div>

    <div id="tab_style_editor" class="mw_toolbar_tab">
      <?php //include( 'toolbar_tag_editor.php') ; ?>
    </div>


    <?php endif; ?>

    <?php include mw_includes_path().'toolbar'.DS.'wysiwyg.php'; ?>
    <?php include mw_includes_path().'toolbar'.DS.'wysiwyg_tiny.php'; ?>

    <div id="mw-saving-loader"></div>


  </div>

  <!-- /end .mw -->
</div>
<?php event_trigger('mw_after_editor_toolbar'); ?>
<!-- /end mw_holder -->

<?php   include mw_includes_path().'toolbar'.DS."design.php"; ?>
<?php   //include "UI.php"; ?>










<?php } else { ?>
<script>





  previewHTML = function(html, index){
      mw.$('.edit').eq(index).html(html);
  }

  window.onload = function(){
    if(window.opener !== null){
        window.opener.mw.$('.edit').each(function(i){
            var html = $(this).html();
            self.previewHTML(html, i);
        });
    }
  }

</script>
<style>

.delete_column{
  display: none;
}

</style>
<?php } ?>