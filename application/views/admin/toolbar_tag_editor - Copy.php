<? include('toolbar_tag_editor_js.php') ; ?>
<? $text_block_classes = "mw_edit_tag_p mw_edit_tag_h1 mw_edit_tag_h2 mw_edit_tag_h3 mw_edit_tag_h4 mw_edit_tag_h5 mw_edit_tag_h6 mw_edit_tag_span  mw_edit_tag_ul  mw_edit_tag_li  mw_edit_tag_div mw_edit_tag_strong mw_edit_tag_code";  ?>

<div class="mw_iframe_header">
  <div class="mw_iframe_header_title"> <img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/1306242104_design.png" align="left" height="25"  class="mw_iframe_header_icon" />Style element</div>
  <a href="javascript:mw_html_tag_delete()" class="mw_nav_button_delete">&nbsp</a> <a href="javascript:mw_sidebar_nav('#mw_sidebar_modules_holder')" class="mw_nav_button_blue_small"> <span> Back </span> </a> </div>
<style>

/*.ui-accordion-header.css_editor_tab_text .ui-icon {
    background: url('<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/1306317754_text_letter_t_1.png');
}
.ui-accordion-header.css_editor_tab_size .ui-icon {
    background: url('<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/1306318564_align_left_1.png');
}
.ui-accordion-header.s3 .ui-icon {
    background: url(http://p.yusukekamiyamane.com/icons/search/fugue/icons/store-medium.png);
}
.ui-accordion-header.s4 .ui-icon {
    background: url(http://p.yusukekamiyamane.com/icons/search/fugue/icons/balloon-facebook-left.png);
}*/

</style>
<div class="mw_admin_box_padding mw_scollable">
  <div class="mw_iframe_sub_header" >
    <table width="95%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Edit the styles of the selected element
          <!--<a target="_blank" href="http://microweber.com">(see how)</a>--></td>
        <td><a target="_blank" href="<? print $config['help_link']; ?>"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/help.png" hspace="5" /></a></td>
      </tr>
      <tr>
        <td align="right"><a  href="javascript:mw_html_tag_remove_styles()"><img  border="0" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/clear_style.png"  /></a></td>
        <td></td>
      </tr>
    </table>
  </div>
  <div id="link_editor_holder" class="mw_editor_accordeon">
    <h3 class="css_editor_tab_text"> <a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/55-network.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Link</span></a> </h3>
    <div class="css_editor_tab_text_inside">
      <table width="100%" border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td><label>Link</label></td>
          <td><input name="mw_edit_link" id="mw_edit_link" class="mw_tag_editor_input"  /></td>
        </tr>
        
        <tr>
          <td><label>Target</label></td>
          <td>
          <input name="mw_edit_link_window" id="mw_edit_link_window" class="mw_tag_editor_input"  />
          </td>
        </tr>

      </table>
    </div>
  </div>
  <div id="image_editor_holder" class="mw_editor_accordeon">
    <h3 class="css_editor_tab_text"> <a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/121-landscape.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Image settings</span></a> </h3>
    <div class="css_editor_tab_text_inside">
      <table width="100%" border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td colspan="2"><img id="image_editor_holder_src" width="100" /></td>
        </tr>
        <tr>
          <td colspan="2"><form enctype="multipart/form-data" method="POST" action="<? print  site_url('api/media/upload') ?>" id="uploadForm" name="uploadForm" encoding="multipart/form-data">
              <input type="hidden" value="1000000" name="MAX_FILE_SIZE">
              <input type="file" name="file" onchange="mw_do_the_image_upload();">
            </form></td>
        </tr>
        <tr>
          <td><label>Thumbnail size</label></td>
          <td><input id="mw_img_size_set" class="mw_img_size mw_tag_editor_input"  type="text" /></td>
        </tr>
        <tr>
          <td><label>Image link</label></td>
          <td><input name="mw_edit_image_link" id="mw_edit_image_link" class="mw_tag_editor_input"  /></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="mw_html_css_editor" class="mw_editor_accordeon">
    <h3 class="css_editor_tab_text"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/text.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Text properties</span></a></h3>
    <div class="css_editor_tab_text_inside">
      <div class="<? print $text_block_classes ?>">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr valign="middle">
            <td><input  name="font-family" class="mw_ac_fonts css_property mw_tag_editor_input mw_tag_editor_input_select_font"  type="text" /></td>
            <td><select name="font-size" dimensions="px" class="mw_ac_sizes css_property mw_tag_editor_input mw_tag_editor_input_select_font_size" type="text" onchange="mw_html_tag_editor_apply_styles()">
                <option value="">Size</option>
                <? for ($i = 1; $i <= 100 ; $i++) : ?>
                <option value="<? print $i ?>"><? print $i ?> px</option>
                <? endfor; ?>
              </select></td>
          </tr>
        </table>
        <br />
        <table border="0" cellpadding="5" cellspacing="5" id="mw_toolbar_text_edit_sidebar">
          <tr>
            <td><a href="javascript:mw_html_tag_editor_apply_style_for_element('font-weight', 'bold', 'normal')"  class="mw_tag_editor_font_style_btns"  css_name="font-weight" css_value_active="bold"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/bold.png"  /></a></td>
            <td><a href="javascript:mw_html_tag_editor_apply_style_for_element('font-style', 'italic', 'normal')"  class="mw_tag_editor_font_style_btns"  css_name="font-style" css_value_active="italic"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/italic.png"  /></a></td>
            <td><a href="javascript:mw_html_tag_editor_apply_style_for_element('font-decoration', 'underline', 'none')"  class="mw_tag_editor_font_style_btns"  css_name="font-decoration" css_value_active="underline"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/underline.png"  /></a></td>
            <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/seperator.png"  /></td>
            <td><a href="javascript:mw_html_tag_editor_apply_style_for_element('text-align', 'left', 'none')"  class="mw_tag_editor_font_style_btns" css_name="text-align" css_value_active="left"   > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/align_left.png"  /> </a> <a href="javascript:mw_html_tag_editor_apply_style_for_element('text-align', 'right', 'none')"  class="mw_tag_editor_font_style_btns" css_name="text-align" css_value_active="right"   > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/align_right.png"  /> </a> <a href="javascript:mw_html_tag_editor_apply_style_for_element('text-align', 'center', 'none')"  class="mw_tag_editor_font_style_btns" css_name="text-align" css_value_active="center"   > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/align_center.png"  /> </a> <a href="javascript:mw_html_tag_editor_apply_style_for_element('text-align', 'justify', 'none')"  class="mw_tag_editor_font_style_btns" css_name="text-align" css_value_active="justify"  > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/align_justify.png"  /> </a></td>
            <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/seperator.png"  /></td>
            <td><input  class="mw_color css_property color mw_tag_editor_input_font_color"  name="color"   type="color"  data-hex="true" style="height:20px;width:20px;"   /></td>
          </tr>
          <tr>
            <td><input type="image" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/heading_1.png"  class="mw_make_element" make_element="h1" value="h1" unselectable="true" /></td>
            <td><input type="image"  class="mw_make_element" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/heading_2.png" make_element="h2" value="h2" unselectable="true" /></td>
            <td><input type="image"  class="mw_make_element" make_element="h3" value="h3" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/heading_3.png" unselectable="true" /></td>
            <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/seperator.png"  /></td>
            <td><input type="image" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/image.gif"    class="mw_make_element" make_element="img" make_element_attr="src" make_element_attr_val="http://lorempixum.com/200/200/?<? print rand(); ?>" value="img"  unselectable="true" /></td>
            <td><input type="image"  class="mw_make_element" make_element="a" value="a" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/world_link.png"  unselectable="true" /></td>
            <td></td>
          </tr>
        </table>
      </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mw_edit_tag_table"  style="display:none">
        <tr class="<? print $text_block_classes ?>">
          <td> font-weight </td>
          <td><select name="font-weight"   class="css_property" type="text">
              <option value="">Default</option>
              <option value="normal">normal</option>
              <option value="bold">bold</option>
              <option value="bolder">bolder</option>
              <option value="lighter">lighter</option>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>font-style</td>
          <td><select name="font-style"   class="css_property" type="text">
              <option value="">Default</option>
              <option value="normal">normal</option>
              <option value="italic">italic</option>
              <option value="oblique">oblique</option>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>text-transform</td>
          <td><select name="text-transform"   class="css_property" type="text">
              <option value="">Default</option>
              <option value="none">none</option>
              <option value="capitalize">capitalize</option>
              <option value="capitalize">capitalize</option>
              <option value="lowercase">lowercase</option>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>text-decoration</td>
          <td><select name="text-decoration"   class="css_property" type="text">
              <option value="">Default</option>
              <option value="none">none</option>
              <option value="underline">underline</option>
              <option value="overline">overline</option>
              <option value="line-through">line-through</option>
              <option value="blink">blink</option>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>text-align</td>
          <td><select name="text-align"   class="css_property" type="text">
              <option value="">Default</option>
              <option value="left">left</option>
              <option value="right">right</option>
              <option value="center">center</option>
              <option value="justify">justify</option>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>letter-spacing</td>
          <td><select name="letter-spacing" dimensions="px" class="mw_ac_sizes css_property" type="text">
              <option value="">Default</option>
              <? for ($i = 1; $i <= 100 ; $i++) : ?>
              <option value="<? print $i ?>"><? print $i ?></option>
              <? endfor; ?>
            </select></td>
        </tr>
        <tr class="<? print $text_block_classes ?>">
          <td>word-spacing</td>
          <td><select name="word-spacing" dimensions="px" class="mw_ac_sizes css_property" type="text">
              <option value="">Default</option>
              <? for ($i = 1; $i <= 100 ; $i++) : ?>
              <option value="<? print $i ?>"><? print $i ?></option>
              <? endfor; ?>
            </select></td>
        </tr>
      </table>
    </div>
    <h3 class="css_editor_tab_size"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/align.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Align and size</span></a></h3>
    <div class="css_editor_tab_size_inside">
      <div class="mw_tag_editor_item_holder">
        <div class="mw_tag_editor_label_wide">Align</div>
        <select class="css_property mw_tag_editor_input mw_tag_editor_input_wide" name="float">
          <option value="">None</option>
          <option value="left">Left</option>
          <option value="right">Right</option>
        </select>
      </div>
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Width</div></td>
            <td><input name="width" class="mw_slider css_property" type="text" /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Height</div></td>
            <td><input name="height" class="mw_slider css_property" type="text" /></td>
          </tr>
        </table>
      </div>
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="0" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Padding</div></td>
            <td><input name="padding" class="mw_slider css_property" dimensions="px" type="text" /></td>
            <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/padding.png" height="30"  /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Margin</div></td>
            <td><input name="margin" class="mw_slider css_property" dimensions="px" type="text" /></td>
            <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/margin.png" height="30"  /></td>
          </tr>
        </table>
      </div>
      <!--   <select class="css_property" name="display">
              <option value="">Default</option>
              <option value="block">block</option>
              <option value="inline">inline</option>
              <option value="inline-block">inline-block</option>
              <option value="list-item">list-item</option>
              <option value="inline-table">inline-table</option>
              <option value="table">table</option>
              <option value="run-in">run-in</option>
              <option value="inherit">inherit</option>
              <option value="none">none</option>
            </select>-->
    </div>
    <h3 class="css_editor_tab_border"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/border.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Border</span></a></h3>
    <div  class="css_editor_tab_border_inside">
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Border style</div></td>
            <td><select class="css_property mw_tag_editor_input" name="border-style">
                <option value="">Default</option>
                <option value="none">None</option>
                <option value="solid">Solid</option>
                <option value="dotted">Dotted</option>
                <option value="dashed">Dashed</option>
                <option value="double">Double</option>
                <option value="groove">Groove</option>
                <option value="ridge">Ridge</option>
                <option value="inset">Inset</option>
                <option value="outset">Outset</option>
                <option value="inherit">Inherit</option>
              </select></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Border width</div></td>
            <td><input name="border-width"  dimensions="px" class="mw_slider css_property" type="text" /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Border radius</div></td>
            <td><input name="border-radius" class="mw_slider css_property" dimensions="px" type="text" /></td>
          </tr>
        </table>
      </div>
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Border color</div></td>
            <td><input  class="mw_color css_property"  name="border-color"    type="color"  data-hex="true"   /></td>
          </tr>
        </table>
      </div>
    </div>
    <h3 class="css_editor_tab_background"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/background.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Background</span></a></h3>
    <div class="css_editor_tab_background_inside">
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Background color</div></td>
            <td><input  class="mw_color css_property"  name="background-color"    type="color"  data-hex="true"    /></td>
          </tr>
        </table>
      </div>
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Background image</div></td>
            <td><input  class="css_property mw_tag_editor_input mw_tag_editor_input_wide"  name="background-image"     /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Background repeat</div></td>
            <td><select class="css_property mw_tag_editor_input mw_tag_editor_input_wide" name="background-repeat">
                <option value="">Default</option>
                <option value="no-repeat">no-repeat</option>
                <option value="repeat-x">repeat-x</option>
                <option value="repeat-y">repeat-y</option>
                <option value="inherit">inherit</option>
              </select></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Background attachment</div></td>
            <td><select class="css_property mw_tag_editor_input mw_tag_editor_input_wide" name="background-attachment">
                <option value="">Default</option>
                <option value="scroll">scroll</option>
                <option value="fixed">fixed</option>
                <option value="inherit">inherit</option>
              </select></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Background position</div></td>
            <td><select class="css_property mw_tag_editor_input mw_tag_editor_input_wide" name="background-position">
                <option value="">Default</option>
                <option value="left top">left top</option>
                <option value="left center">left center</option>
                <option value="left bottom">left bottom</option>
                <option value="right top">right top</option>
                <option value="right center">right center</option>
                <option value="right bottom">right bottom</option>
                <option value="center top">center top</option>
                <option value="center center">center center</option>
                <option value="center bottom">center bottom</option>
              </select></td>
          </tr>
        </table>
      </div>
      
     
      
      
      <div class="mw_tag_editor_item_holder">
        <table border="0" cellspacing="5" cellpadding="0" >
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Shadow horizontal</div></td>
            <td><input  class="mw_slider css_property css_fx"  name="box-shadow-horizontal"  max="20"  dimensions="px"  type="text"      /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Shadow vertical</div></td>
            <td> <input  class="mw_slider css_property css_fx"  name="box-shadow-vertical"  max="20" dimensions="px"   type="text"      /></td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Shadow blur</div></td>
            <td>                <input  class="mw_slider css_property css_fx" max="20"  name="box-shadow-blur" dimensions="px"   type="text"      />
</td>
          </tr>
          <tr valign="middle">
            <td><div class="mw_tag_editor_label_wide">Shadow color</div></td>
            <td> <input  class="css_property css_fx"  name="box-shadow-spread"  dimensions="px"  type="hidden"      />
                <input  class="css_property css_fx mw_color"  name="box-shadow-color"    type="color"  data-hex="true"      />
             </td>
          </tr>
        </table>
      </div>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      <input name="mw_css_editor_element_id" id="mw_css_editor_element_id" value="" type="text" />
    </div>
    <h3 class="css_editor_tab_effects"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/background.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Shadow</span></a></h3>
    <div class="css_editor_tab_effects_inside">
      <div class="mw_tag_editor_item_holder">
        
      </div>
    </div>
  </div>
</div>
<div style="display:none">
  <h3 class="css_editor_tab_rotate"><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/css_editor/background.png"  class="css_editor_accordeon_icon" /><span class="css_editor_accordeon_text">Rotate</span></a></h3>
  <div class="css_editor_tab_effects_inside">
    <div class="mw_tag_editor_item_holder">
      <textarea id="transforms_cssOut"></textarea>
      <div id="transforms-box" style="display:none"></div>
      <label for="scale-input">Scale :</label>
      <input name="scale-input" id="scale-input" onchange="setTransform()" class="mw_slider" size="3" value="1" type="text">
      <label for="rotate-input">Rotate :</label>
      <input name="rotate-input" id="rotate-input" onchange="setTransform()" class="mw_slider" size="3" value="0" type="text">
      <span class="size_class">deg</span>
      <label for="tran1-input">Translate X:</label>
      <input name="tran1-input" id="tran1-input" onchange="setTransform()" class="textbox" size="3" value="0" type="text">
      <span class="size_class">px</span>
      <label for="tran2-input">Translate Y:</label>
      <input name="tran2-input" id="tran2-input" onchange="setTransform()" class="mw_slider" size="3" value="0" type="text">
      <span class="size_class">px</span>
      <label for="skew1-input">Skew1 X:</label>
      <input name="skew1-input" id="skew1-input" onchange="setTransform()" class="mw_slider" size="3" value="0" type="text">
      <span class="size_class">deg</span>
      <label for="skew2-input">Skew Y:</label>
      <input name="skew2-input" id="skew2-input" onchange="setTransform()" class="mw_slider" size="3" value="0" type="text">
      <span class="size_class">deg</span> </div>
  </div>
</div>
