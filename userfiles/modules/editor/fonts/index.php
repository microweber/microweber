<?php 
if(isset($params['module_settings'])){
return include(__DIR__.DS.'admin.php');	
}
?>
<div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family"  title="<?php _e("Font"); ?>" data-value="Arial"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val">Arial</span> </span>
  <div class="mw-dropdown-content">
    <ul>
      <li value="Arial"><a href="#" style="font-family:Arial">Arial</a></li>
      <li value="Tahoma"><a href="#" style="font-family:Tahoma">Tahoma</a></li>
      <li value="Verdana"><a href="#" style="font-family:Verdana">Verdana</a></li>
      <li value="Georgia"><a href="#" style="font-family:Georgia">Georgia</a></li>
      <li value="Times New Roman"><a href="#" style="font-family: 'Times New Roman'">Times New Roman</a></li>
    </ul>
    <a class="text-center" href="javascript:;" onClick="mw.drag.module_settings('#<?php print $params['id'] ?>','admin');">...</a>
  </div>
</div>
