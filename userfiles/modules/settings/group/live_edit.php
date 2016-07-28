<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<h2>
  <?php _e("Live Edit"); ?>
  <?php _e("settings"); ?>
</h2>
<div class="<?php print $config['module_class'] ?>">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Enable keyboard shortcuts"); ?>
    </label>
    <?php
        $disable_keyboard_shortcuts = get_option('disable_keyboard_shortcuts','website');
         
    ?>
    <select name="disable_keyboard_shortcuts" class="mw-ui-field mw_option_field"   type="text" option-group="website">
      <option value="" <?php if(!$disable_keyboard_shortcuts): ?> selected="selected" <?php endif; ?>>
      <?php _e("Yes"); ?>
      </option>
      <option value="1" <?php if($disable_keyboard_shortcuts): ?> selected="selected" <?php endif; ?>>
      <?php _e("No"); ?>
      </option>
    </select>
  </div>
  <?php

$enabled_custom_fonts = get_option("enabled_custom_fonts", "template");

 
?>
</div>
