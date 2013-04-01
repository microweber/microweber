<?php if(!isset($_GET['preview'])){ ?>
<script type="text/javascript">
  document.body.className+=' loading';

  //mw.require("<?php print( INCLUDES_URL);  ?>js/jquery.js");

  typeof jQuery == 'undefined' ? mw.require("<? print INCLUDES_URL; ?>js/jquery-1.9.1.js") : '' ;
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
  mw.require("lab.js");

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


            mw.history.init();
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


        });




    </script>
<?
			 $back_url = site_url().'admin/view:content';
			 if(defined('CONTENT_ID')){
				 $back_url .= '#action=editpage:'.CONTENT_ID; 
			 } 
			 
			  if(isset($_COOKIE['back_to_admin'])){
				  
				 $back_url = $_COOKIE['back_to_admin'];   
				 
			  }
			 
			 
			 
			  ?>
<span id="show_hide_sub_panel" onclick="mw.toggle_subpanel();"><span id="show_hide_sub_panel_slider"></span><span id="show_hide_sub_panel_info">Less</span></span> <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green" style=" position: fixed; right: 87px;top: 9px;z-index: 999;" onclick="mw.drag.save()"><span>Save</span></span>
<div class="mw-defaults" id="live_edit_toolbar_holder">
  <div  id="live_edit_toolbar">
    <div id="mw_toolbar_nav"> <a href="#tab=modules" id="mw_toolbar_logo">Microweber - Live Edit</a>
      <?php /* <a href="javascript:;" style="position: absolute;top: 10px;right: 10px;" onclick="mw.extras.fullscreen(document.body);">Fullscreen</a> */  ?>

        <ul id="mw_tabs">
          <li id="t_modules"> <a href="#tab=modules" onclick="mw.url.windowHashParam('tab', 'modules');return false;">
            <? _e('Modules'); ?>
            </a> </li>
          <li id="t_layouts"> <a href="#tab=layouts" onclick="mw.url.windowHashParam('tab', 'layouts');return false;">
            <? _e('Layouts'); ?>
            </a> </li>
          <li id="t_pages"> <a href="#tab=pages" onclick="mw.url.windowHashParam('tab', 'pages');return false;">
            <? _e('Pages'); ?>
            </a> </li>
          <li id="t_help"> <a href="#tab=help" onclick="mw.url.windowHashParam('tab', 'help');return false;">
            <? _e('Help'); ?>
            </a> </li>
        </ul>

       <div class="mw-ui-dropdown media-small" id="mw_tabs_small">
          <span class="mw-ui-btn">Menu<span class="ico idownarr right"></span></span>
          <div class="mw-dropdown-content">
            <ul class="mw-dropdown-list">
              <li id="t_modules"> <a href="#tab=modules" onclick="mw.url.windowHashParam('tab', 'modules');return false;">
                <? _e('Modules'); ?>
                </a> </li>
              <li id="t_layouts"> <a href="#tab=layouts" onclick="mw.url.windowHashParam('tab', 'layouts');return false;">
                <? _e('Layouts'); ?>
                </a> </li>
              <li id="t_pages"> <a href="#tab=pages" onclick="mw.url.windowHashParam('tab', 'pages');return false;">
                <? _e('Pages'); ?>
                </a> </li>
              <li id="t_help"> <a href="#tab=help" onclick="mw.url.windowHashParam('tab', 'help');return false;">
                <? _e('Help'); ?>
                </a> </li>
            </ul>
         </div>
       </div>
      <?php /*<a href="#design_bnav" class="mw-ui-btn mw-ui-btn-revert ed_btn mw_ex_tools" style="margin: 11px 0 0 12px; "><span></span>Design</a>*/ ?>
      <div id="mw-toolbar-right"> <a class="mw-ui-btn back_to_admin" href="<?php print $back_url; ?>"><span class="backico"></span>Back to Admin</a>
        <?php /*<a href="javascript:;" class="mw-ui-btn" onclick="mw.iphonePreview();"><span class="ico iPhone"></span>iPhone</a>*/   ?>
        <div class="mw-ui-dropdown right"> <a href="<? print curent_url(); ?>/editmode:n" class="mw-ui-btn">View Website<span class="ico idownarr right"></span></a>
        <div class="mw-dropdown-content">
        <ul class="mw-dropdown-list">
            <li><a href="<? print curent_url(); ?>/editmode:n">View Website</a></li>
            
            
            
            
            
             <li>
             
             
             <a href="<?php print $back_url; ?>">Back to admin</a></li>
            <li><a href="javascript:;" onclick="mw.preview();void(0);">Preview</a></li>
            <? if(defined('CONTENT_ID') and CONTENT_ID > 0): ?>
              <li><a href="javascript:;">Unpublish<? print CONTENT_ID; ?></a></li>
              <li><a href="javascript:;">Publish</a></li>
            <? endif; ?>
          </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="tab_modules" class="mw_toolbar_tab">
      <microweber module="admin/modules/categories_dropdown" />
      <div class ="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
      <div class="mw_clear">&nbsp;</div>
    </div>
    <div id="tab_layouts" class="mw_toolbar_tab">
      <microweber module="admin/modules/categories_dropdown" data-for="elements" />
      <div class="modules_bar_slider bar_slider">
        <div class="modules_bar">
          <microweber module="admin/modules/list_elements" />
        </div>
        <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span> </div>
    </div>
    <div id="tab_pages" class="mw_toolbar_tab">
      <p class="left">Here you can easely manage your website pages and posts. Try the functionality below. <a href="#">You can see the tutorials here</a>.</p>
      <a href="#" class="right mw-ui-btn"><span class="mw-ui-btn-plus"></span>Add New</a>
      <iframe
            onload="mw.tools.iframeLinksToParent(this);"
            frameborder="0"
            scrolling="auto"
            id="mw_edit_pages"
            data-src="<?php print site_url(); ?>admin/view:content?no_toolbar=1<? if(defined('CONTENT_ID')) : ?>/#action=editpage:<? print CONTENT_ID ?><? endif; ?>"
            src="#"> </iframe>
    </div>
    <div id="tab_help" class="mw_toolbar_tab">Help <a href="<?php print site_url('admin'); ?>">Admin</a></div>
    <div id="tab_style_editor" class="mw_toolbar_tab">
      <? //include( 'toolbar_tag_editor.php') ; ?>
    </div>
    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg.php'; ?>
    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg_tiny.php'; ?>
    <div id="mw-history-panel"></div>
    <div id="mw-saving-loader"></div>
  </div>

  <!-- /end .mw -->
</div>
<!-- /end mw_holder -->

<?php   include "design.php"; ?>
<?php   include "UI.php"; ?>
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
