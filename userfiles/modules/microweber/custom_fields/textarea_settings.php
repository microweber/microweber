<?php include('settings_header.php'); ?>



<?php 
if (!isset($data['rows'])) {
	$data['rows'] = 5;
}
?>

<style>
    #mw-custom-fields-text-holder textarea {
      resize:both;
    }
</style>

 <div class="custom-field-settings-name">
               
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>

    <input type="text" onkeyup="" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">

    <br />
    <br />
     
    <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option mw-full-width" name="options[required]"  <?php if(isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?> value="1"><span></span><span><?php _e("Required"); ?>?</span></label>


</div>


   <div class="custom-field-settings-values">
   
    <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
        <div id="mw-custom-fields-text-holder">
         <textarea class="mw-ui-field mw-full-width" name="value"><?php print ($data['value']) ?></textarea>
        </div>
    </div>
    
     <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Textarea Rows"); ?></label>
        <div id="mw-custom-fields-text-holder">
          <input type="number" onkeyup="" class="mw-ui-field mw-full-width" value="<?php print ($data['rows']) ?>" name="options[rows]" id="input_field_label<?php print $rand; ?>">
        </div>
    </div>
    
    <?php print $savebtn; ?>
    </div>


<div class="mw-custom-field-group">
    <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<?php include('settings_footer.php'); ?>
