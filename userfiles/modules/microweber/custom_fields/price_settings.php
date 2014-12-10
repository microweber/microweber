<?php include('settings_header.php'); ?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>
  <input type="text" class="mw-ui-field" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<?php 
    if($data['value'] == ''){
        $data['value'] = 0;
    }
?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="value<?php print $rand; ?>">Value <b><?php print mw()->shop_manager->currency_symbol($curr=false,$key=3); ?> </b></label>
  <input type="text"
        class="mw-ui-field"
        name="value"
        value="<?php print ($data['value']) ?>" />
</div>
<?php include('settings_footer.php'); ?>