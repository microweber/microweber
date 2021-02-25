<?php include('settings_header.php'); ?>

<?php
$curr_symbol = mw()->shop_manager->currency_symbol();
?>

<div class="mw-ui-field-holder">
  <label class="control-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
      <small class="text-muted d-block mb-2"><?php _e('The name of your field');?></small>
  </label>
  <input type="text" class="form-control" value="<?php print _e(($data['name'])) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<?php 
    if($data['value'] == ''){
        $data['value'] = 0;
    }
?>
<div class="mw-ui-field-holder">
  <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value in "); ?><b><?php print $curr_symbol; ?> </b></label>
    <small class="text-muted d-block mb-2"><?php _e('Your price');?></small>

  <input type="text"
        class="form-control"
        name="value"
        value="<?php print ($data['value']) ?>" />
</div>


<?php
$settings['id'] = $data['id'];
event_trigger('mw.admin.custom_fields.price_settings', $settings);
?>


<?php include('settings_footer.php'); ?>