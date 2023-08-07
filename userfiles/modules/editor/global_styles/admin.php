<?php must_have_access(); ?>
<script  type="text/javascript">


//mw.require('options.js', true);


</script>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _ejs("Template styles are saved"); ?>.");



    });
});
</script>

<div id="mw-theme-settings" contenteditable="false"> <span class="btn btn-default btn-medium" onclick="mw.$('#mw-theme-settings-content').toggle();"><?php _e('Template Settings'); ?>&nbsp;<span class="icon-align-justify"></span></span>
  <div id="mw-theme-settings-content">
    <hr>





    <label ><small>Font</small></label>




        <input  name="font-size" option-group="<?php print TEMPLATE_NAME; ?>"     value="<?php print get_option('font-size', TEMPLATE_NAME); ?>"     class="form-select  mw_option_field" type="text" />
       <label ><small><?php _e('Font'); ?></small></label>




        <input  name="background-color" option-group="<?php print TEMPLATE_NAME; ?>"     value="<?php print get_option('background-color', TEMPLATE_NAME); ?>"     class="form-select  mw_option_field" type="text" />


    <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family hovered" id="mwthemesettings-fontfamily" data-value="Arial" style="width:210px;float:none"> <span class="mw-dropdown-value">  <span class="mw-dropdown-val">Arial</span> </span>
      <div class="mw-dropdown-content">
        <ul style="width: 100%;">
          <li value="Droid Serif"><a href="javascript:;" style="font-family:Droid Serif">Droid Serif</a></li>
          <li value="Arial"><a href="javascript:;" style="font-family:Arial">Arial</a></li>
          <li value="Tahoma"><a href="javascript:;" style="font-family:Tahoma">Tahoma</a></li>
          <li value="Verdana"><a href="javascript:;" style="font-family:Verdana">Verdana</a></li>
          <li value="Georgia"><a href="javascript:;" style="font-family:Georgia">Georgia</a></li>
          <li value="Times New Roman"><a href="javascript:;" style="font-family: 'Times New Roman'">Times New Roman</a></li>
        </ul>
      </div>
    </div>
    <hr>
    <label><small><?php _e('Font Size'); ?></small></label>
    <div
    data-value="13"
    data-min="8"
    data-max="22"
    data-custom="set_body_font_size"
    id="mwthemesettings-fontsize"
    class="ed_slider es_item"></div>
    <hr>
    <label><small><?php _e('Content Background Color'); ?></small></label>
    <span class="btn btn-default" id="mwthemesettings-bgcolor" onclick="setcontent_bg()" style=""><i class="icon-picture"></i></span>
    <hr>
    <label><small><?php _e('Content Background Media'); ?></small></label>
    <span class="btn btn-default" id="mwthemesettings-bgimage" onclick="content_bg_media()" style=""><i class="icon-picture"></i></span>
    <hr>
    <label><small><?php _e('Header Background Color'); ?></small></label>
    <span class="btn btn-default" id="mwthemesettings-bgcolor-header" onclick="setheader_bg()" style=""><i class="icon-picture"></i></span> </div>
</div>
<script type="text/javascript">




set_body_font_size = function(size){
  document.body.style.fontSize = size + "px";
}

setcontent_bg = function(){
  mw.wysiwyg.external_tool(document.getElementById('mwthemesettings-bgcolor'), mw.external_tool('color_picker') + '#content_bg_color');
  $(mw.wysiwyg.external).find("iframe").width(280).height(320);
}

content_bg_color = function(a){
  document.getElementById('content').style.backgroundColor = "#"+a;
  document.getElementById('content').style.backgroundImage = "none";
}

setheader_bg = function(){
  mw.wysiwyg.external_tool(document.getElementById('mwthemesettings-bgcolor-header'), mw.external_tool('color_picker') + '#header_bg_color');
  $(mw.wysiwyg.external).find("iframe").width(280).height(320);
}

header_bg_color = function(a){
  document.getElementById('header-main').style.backgroundColor = "#"+a;
  document.getElementById('header-main').style.backgroundImage = "none";
}

content_bg_media = function(){
  mw.dialogIframe({
      url:"rte_image_editor#set_content_bg_media",
      //title:"Upload Picture",
      name:"mw_rte_image",
      width:430,
      height:210,
      template:'mw_modal_simple'
    });
}

set_content_bg_media = function(a){
  document.getElementById('content').style.backgroundImage = "url(" + a + ")";
  document.getElementById('content').style.backgroundRepeat = "no-repeat";
}


$(document).ready(function(){
    mw.$("#mwthemesettings-fontfamily").on("change", function(){
          document.body.style.fontFamily = $(this).getDropdownValue();
    });
});


</script>
