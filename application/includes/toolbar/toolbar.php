<?php if(!isset($_GET['preview'])){ ?>

<script type="text/javascript">
  document.body.className+=' loading';

  //mw.require("<?php print( INCLUDES_URL);  ?>js/jquery.js");
  mw.require("http://code.jquery.com/jquery-1.8.3.min.js");
  mw.require("liveadmin.js");
  mw.require("<?php print( INCLUDES_URL);  ?>js/jquery-ui-1.8.20.custom.js");
  //mw.require("http://raw.github.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js");
  mw.require("events.js");
  mw.require("url.js");
  mw.require("tools.js");
  mw.require("wysiwyg.js");
  mw.require("css_parser.js");
  mw.require("style_editors.js");
  mw.require("forms.js");
  mw.require("files.js");

  //mw.require("keys.js");



</script>






<link href="<?php   print( INCLUDES_URL);  ?>api/api.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/mw_framework.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( INCLUDES_URL);  ?>css/toolbar.css" rel="stylesheet" type="text/css" />
<?php /*  */ ?>
<?php /* <script src="http://c9.io/ooyes/mw/workspace/sortable.js" type="text/javascript"></script>  */ ?>
<script src="<?php   print( INCLUDES_URL);  ?>js/sortable.js?v=<?php echo uniqid(); ?>" type="text/javascript"></script>
<script src="<?php   print( INCLUDES_URL);  ?>js/toolbar.js?v=<?php echo uniqid(); ?>" type="text/javascript"></script>
<script type="text/javascript">



        $(document).ready(function () {

         // mw.drag.create();


            mw.history.init();
            mw.tools.module_slider.init();
            mw.tools.dropdown();

            mw.tools.toolbar_slider.init();


            mw.liveadmin.menu.size(130);

            $(window).resize(function(){
                 mw.liveadmin.menu.size(130);
            });



        });




    </script>

<span id="show_hide_sub_panel" onclick="mw.toggle_subpanel();"><span id="show_hide_sub_panel_slider"></span><span id="show_hide_sub_panel_info">Hide</span></span>
<div class="mw" id="live_edit_toolbar_holder">
  <div class="mw" id="live_edit_toolbar">
    <div id="mw_toolbar_nav"> <a href="#tab=modules" id="mw_toolbar_logo">Microweber - Live Edit</a>
      <?php /* <a href="javascript:;" style="position: absolute;top: 10px;right: 10px;" onclick="mw.extras.fullscreen(document.body);">Fullscreen</a> */  ?>
      <div id="mw-menu-liquify"><ul id="mw_tabs">
        <li id="t_modules">
            <a href="#tab=modules" onclick="mw.url.windowHashParam('tab', 'modules');return false;"><? _e('Modules'); ?></a>
        </li>
        <li id="t_layouts">
            <a href="#tab=layouts" onclick="mw.url.windowHashParam('tab', 'layouts');return false;"><? _e('Layouts'); ?></a>
        </li>
        <li id="t_pages">
            <a href="#tab=pages" onclick="mw.url.windowHashParam('tab', 'pages');return false;"><? _e('Pages'); ?></a>
        </li>
        <li id="t_help">
          <a href="#tab=help" onclick="mw.url.windowHashParam('tab', 'help');return false;"><? _e('Help'); ?></a>
        </li>
      </ul></div>
      <div id="menu-dropdown" onclick="mw.tools.toggle('#menu-dropdown-nav', this);"><div id="menu-dropdown-nav"></div></div>
       <a href="#design_bnav" class="mw-ui-btn-rect mw-ui-btn-rect-revert ed_btn mw_ex_tools" style="margin: 11px 0 0 12px; "><span></span>Design</a>
      <div id="mw-toolbar-right">




        <a class="mw-ui-btn back_to_admin" href="<?php print site_url(); ?>admin/view:content<? if(defined('CONTENT_ID')) : ?>?edit_content=<? print CONTENT_ID ?><? endif; ?>"><span class="backico"></span>Back to Admin</a>
        <span onclick="mw.preview();" class="mw-ui-btn unselectable">Preview</span>
        <span class="mw-ui-btn mw-ui-btn-blue">Publish</span>
      </div>
      </div>

    <div id="tab_modules" class="mw_toolbar_tab">
      <microweber module="admin/modules/categories_dropdown" />
      <div class="modules_bar_slider bar_slider">
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
            src="<?php print site_url(); ?>admin/view:content?no_toolbar=1<? if(defined('CONTENT_ID')) : ?>&edit_content=<? print CONTENT_ID ?><? endif; ?>">
        </iframe>



    </div>
    <div id="tab_help" class="mw_toolbar_tab">Help <a href="<?php print site_url('admin'); ?>">Admin</a></div>
    <div id="tab_style_editor" class="mw_toolbar_tab">
      <? //include( 'toolbar_tag_editor.php') ; ?>
    </div>
    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg.php'; ?>
    <span class="mw_editor_btnz ed_btn" onclick="mw.drag.save()"
        style="position: fixed;top: 133px;right:30px; z-index: 2000;">Save</span>

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

