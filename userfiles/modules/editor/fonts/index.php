<?php
if(isset($params['module_settings'])){
return include(__DIR__.DS.'admin.php');
}
?>
<?php $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFontsAsString();


$enabled_custom_fonts_array = array();

if (is_string($enabled_custom_fonts) and $enabled_custom_fonts) {
    $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
}

?>

<script>

    $( document ).ready(function() {
      mw.dropdown();
      $('#<?php print $params['id'] ?>').removeClass( "module" )
    });
</script>
<div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family"  title="<?php _e("Font"); ?>" data-value="Arial" style="width: 100%;">
    <span class="mw-dropdown-value">

        <span class="mw-dropdown-val">Arial</span>
    </span>
  <div class="mw-dropdown-content">
    <ul>
      <li value="Arial"><a href="#" style="font-family:Arial">Arial</a></li>
      <li value="Tahoma"><a href="#" style="font-family:Tahoma">Tahoma</a></li>
      <li value="Verdana"><a href="#" style="font-family:Verdana">Verdana</a></li>
      <li value="Georgia"><a href="#" style="font-family:Georgia">Georgia</a></li>
      <li value="Times New Roman"><a href="#" style="font-family: 'Times New Roman'">Times New Roman</a></li>

        <?php foreach ($enabled_custom_fonts_array as $font): ?>
            <li value="<?php print $font; ?>'"><a href="#" style="font-family:'<?php print $font; ?>'"><?php print $font; ?></a></li>
        <?php endforeach; ?>



    </ul>
    <a href="javascript:;" onClick="mw.top().drag.module_settings('#<?php print $params['id'] ?>','admin');" style="background-color: #f5f5f5; text-align: center; padding-top: 2px; padding-bottom: 5px;"><small style="text-transform: lowercase; color: black;"><?php _e('more'); ?></small></a>
  </div>
</div>
