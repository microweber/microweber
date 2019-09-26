<?php include('settings_header.php'); ?>

<?php
$curr_symbol = mw()->shop_manager->currency_symbol();
?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>
  <input type="text" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<?php 
    if($data['value'] == ''){
        $data['value'] = 0;
    }
?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="value<?php print $rand; ?>">Value <b><?php print $curr_symbol; ?> </b></label>
  <input type="text"
        class="mw-ui-field mw-full-width"
        name="value"
        value="<?php print ($data['value']) ?>" />
</div>


<?php
$settings['id'] = $data['id'];
event_trigger('mw.admin.custom_fields.price_settings', $settings);
?>


<?php include('settings_footer.php'); ?>