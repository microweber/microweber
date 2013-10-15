<?php if(!isset($_GET['preview'])){ ?>
<script type="text/javascript">
    mw.settings.liveEdit = true;
    mw.require("liveadmin.js");
    mw.require("<?php print( MW_INCLUDES_URL);  ?>js/jquery-ui-1.10.0.custom.min.js");
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
    mw.require("<?php   print( MW_INCLUDES_URL);  ?>js/sortable.js");
    mw.require("<?php   print( MW_INCLUDES_URL);  ?>js/toolbar.js");
</script>
<link href="<?php print( MW_INCLUDES_URL);  ?>api/api.css" rel="stylesheet" type="text/css" />
<link href="<?php print( MW_INCLUDES_URL);  ?>css/mw_framework.css" rel="stylesheet" type="text/css" />
<link href="<?php print( MW_INCLUDES_URL);  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />
<link href="<?php print( MW_INCLUDES_URL);  ?>css/toolbar.css" rel="stylesheet" type="text/css" />
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

<div class="mw-defaults" id="live_edit_toolbar_holder">
  <div  id="live_edit_toolbar">
    <?php include MW_INCLUDES_DIR.'toolbar'.DS.'wysiwyg.php'; ?>
    <div id="modules-and-layouts" style="">
         <div class="toolbars-search">
            <span
                 class="mw-pin mwt-pin"
                 data-for="#modules-and-layouts,#tab_modules,.tst-modules">
            </span>
             <div class="mw-autocomplete left">
                 <input
                      type="mwautocomplete"
                      autocomplete="off"
                      id="modules_switcher"
                      data-for="modules"
                      class="mwtb-search mwtb-search-modules"
                      placeholder="Modules"/>
                 <div class="mw-autocomplete-cats mw-autocomplete-cats-modules">
                    <module
                          type="categories"
                          data-no-wrap=1
                          template="liveedit_toolbar"
                          data-for="modules" />
                 </div>
                 <div class="mw-autocomplete-cats mw-autocomplete-cats-elements">
                    <module
                          type="categories"
                          data-no-wrap=1
                          template="liveedit_toolbar"
                          data-for="elements" />
                 </div>
                 <div class="mw_clear"></div>

                 <button class="mw-ui-btn mw-ui-btn-medium" id="modules_switch">Layouts</button>

             </div>
         </div>
        <div id="tab_modules" class="mw_toolbar_tab">
            <div class ="modules_bar_slider bar_slider">
              <div class="modules_bar">
                <module type="admin/modules/list" />
              </div>
              <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span>
            </div>
            <div class="mw_clear">&nbsp;</div>
        </div>
        <div id="tab_layouts" class="mw_toolbar_tab">
          <div class="modules_bar_slider bar_slider">
            <div class="modules_bar">
              <module type="admin/modules/list_layouts" />
            </div>
            <span class="modules_bar_slide_left">&nbsp;</span> <span class="modules_bar_slide_right">&nbsp;</span>
          </div>
        </div>
    </div>
    <?php include MW_INCLUDES_DIR.'toolbar'.DS.'wysiwyg_tiny.php'; ?>
    <div id="mw-saving-loader"></div>
  </div>
</div>
<div id="image_settings_modal_holder" style="display: none">
    <div class='image_settings_modal'>
        <img src='' id='mwimagecurrent' />
        <hr>
        <span title="Vintage Effect" onclick="mw.image.vintage(mw.image.current);" class="ed_item ed_nobg ed_item_image_gray left"> <span></span> </span>
        <span title="Convert to Grayscale" onclick="mw.image.grayscale(mw.image.current);" class="ed_item ed_nobg ed_item_image_gray left"> <span></span> </span>
        <span onclick="mw.image.rotate(mw.image.current);" class="ed_item ed_nobg ed_item_image_rotate left"> <span></span> </span>
        <div class='vSpace'></div><hr>
        <textarea placeholder='Image Description'>"+title+"</textarea>
        <span class='mw-ui-btn mw-ui-btn-green' onclick='mw.current_element.src = mw.image.current.src'>Update</span>
    </div>
</div>
<?php event_trigger('mw_after_editor_toolbar'); ?>
<?php   include MW_INCLUDES_DIR.'toolbar'.DS."design.php"; ?>
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