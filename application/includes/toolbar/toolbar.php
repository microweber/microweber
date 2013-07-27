<?php if(!isset($_GET['preview'])){ ?>
<script type="text/javascript">
  //document.body.className+=' loading';

  //mw.require("<?php print( INCLUDES_URL);  ?>js/jquery.js");

  mw.settings.liveEdit = true;

  typeof jQuery == 'undefined' ? mw.require("<?php print INCLUDES_URL; ?>js/jquery-1.9.1.js") : '' ;
  mw.require("liveadmin.js");
  mw.require("<?php print( INCLUDES_URL);  ?>js/jquery-ui-1.10.0.custom.min.js");
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

  //mw.require("keys.js");



</script>
<link href="<?php   print( INCLUDES_URL);  ?>api/api.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/mw_framework.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/toolbar.css" rel="stylesheet" type="text/css" />
<?php /*  */ ?>
<?php /* <script src="http://c9.io/ooyes/mw/workspace/sortable.js" type="text/javascript"></script>  */ ?>
<script src="<?php   print( INCLUDES_URL);  ?>js/sortable.js" type="text/javascript"></script>
<script src="<?php   print( INCLUDES_URL);  ?>js/toolbar.js" type="text/javascript"></script>
<script type="text/javascript">



        $(document).ready(function () {

         // mw.drag.create();
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
<span id="show_hide_sub_panel" onclick="mw.toggle_subpanel();"><span id="show_hide_sub_panel_slider"></span><span id="show_hide_sub_panel_info"><?php _e("Less"); ?></span></span>


<div id="mw-toolbar-right" class="mw-defaults">


<div class="mw-ui-dropdown right" id="history_dd">
          <a class="mw-ui-btn mw-ui-btn-hover mw-btn-single-ico" onclick="mw.$('#historycontainer').toggle();" title="<?php _e("History"); ?>"><span class="ico ihistory" style="height: 22px;"></span></a>
          <div class="mw-dropdown-content" style="width: 150px;right: -50px;left: auto;display: none;visibility: visible" id="historycontainer">
            <ul class="mw-dropdown-list">
                <li>
                    <div id="mw-history-panel"></div>
                </li>
            </ul>
          </div>
        </div>

      <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-ui-btn right" onclick="mw.drag.save(this)" id="main-save-btn"><?php _e("Save"); ?></span>


        <a title="Back to Admin" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-blue back_to_admin" href="<?php print $back_url; ?>"><?php _e("Back to Admin"); ?></a>



        <?php /*<a href="javascript:;" class="mw-ui-btn" onclick="mw.iphonePreview();"><span class="ico iPhone"></span>iPhone</a>*/   ?>
        <div class="mw-ui-dropdown right"> <a href="<?php print mw('url')->current(); ?>/editmode:n" class="mw-ui-btn mw-ui-btn-medium" style="margin-left: 0;"><?php _e("Actions"); ?><span class="ico idownarr right"></span></a>
          <div class="mw-dropdown-content" style="width: 155px;">

            <ul class="mw-dropdown-list">
              <li><a href="<?php print mw('url')->current(); ?>?editmode=n"><?php _e("View Website"); ?></a></li>

              <li><a href="#" onclick="mw.preview();void(0);"><?php _e("Preview"); ?></a></li>
              <?php if(defined('CONTENT_ID') and CONTENT_ID > 0): ?>
              <?php $pub_or_inpub  = mw('content')->get_by_id(CONTENT_ID); ?>
              <li class="mw-set-content-unpublish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] != 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.unpublish('<?php print CONTENT_ID; ?>')"><?php _e("Unpublish"); ?></a></li>
              <li class="mw-set-content-publish" <?php if(isset($pub_or_inpub['is_active']) and $pub_or_inpub['is_active'] == 'y'): ?> style="display:none" <?php endif; ?>><a href="javascript:mw.content.publish('<?php print CONTENT_ID; ?>')"><?php _e("Publish"); ?></a></li>
              <?php endif; ?>
              <li><a href="<?php print api_url('logout'); ?>"><?php _e("Logout"); ?></a></li>
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
          <?php _e('Pages'); ?>
          </a> </li>
        <li id="t_help"> <a href="#tab=help" onclick="mw.url.windowHashParam('tab', 'help');return false;">
          <?php _e('Help'); ?>
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
      <p class="left"><?php _e("Here you can easely manage your website pages and posts. Try the functionality below."); ?> <a href="#"><?php _e("You can see the tutorials here"); ?></a>.</p>
      <a href="#" class="right mw-ui-btn"><span class="mw-ui-btn-plus"></span><?php _e("Add New"); ?></a>
      <iframe
            onload="mw.tools.iframeLinksToParent(this);"
            frameborder="0"
            scrolling="auto"
            id="mw_edit_pages"
            data-src="<?php print site_url(); ?>admin/view:content?no_toolbar=1<?php if(defined('CONTENT_ID')) : ?>/#action=editpage:<?php print CONTENT_ID ?><?php endif; ?>"
            src="#"> </iframe>
    </div>
    <div id="tab_help" class="mw_toolbar_tab">

      <p style="padding: 31px;text-align: center">Currently Help section is under construction. Please visit <a target="_blank" class="mw-ui-link" href="http://microweber.com">www.microweber.com</a> for more information.</p>

    </div>
    <div id="tab_style_editor" class="mw_toolbar_tab">
      <?php //include( 'toolbar_tag_editor.php') ; ?>
    </div>


    <?php endif; ?>

    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg.php'; ?>
    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg_tiny.php'; ?>

    <div id="mw-saving-loader"></div>


  </div>

  <!-- /end .mw -->
</div>
<?php exec_action('mw_after_editor_toolbar'); ?>
<!-- /end mw_holder -->

<?php   include INCLUDES_DIR.'toolbar'.DS."design.php"; ?>
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