<?php include('settings_header.php'); ?>
<script>
if(!!mw.custom_fields.address_settings){
    mw.custom_fields.address_settings = {
    };
}

mw.$("#mw-custom-fields-address-fields-selector input").commuter(function(){
  var f = $(this).dataset('for');
  mw.$('#mw-custom-fields-address-fields-'+f).slideDown('fast');
  mw.$('#mw-custom-fields-address-fields-'+f + " input").removeAttr('disabled');

}, function(){
  var f = $(this).dataset('for');
  mw.$('#mw-custom-fields-address-fields-'+f).slideUp('fast');
  mw.$('#mw-custom-fields-address-fields-'+f + " input").attr('disabled', 'disabled');

});
</script>

<style>
#mw-custom-fields-address-fields-selector{
  padding: 10px 0;
}

#mw-custom-fields-address-fields-selector div{
  padding: 5px 0
}

#mw-custom-fields-address-fields-selector .mw-ui-check input[type="checkbox"] + span{
  float: left;
  margin-inline-end: 6px;
  top: 3px;
}
</style>
<?php
$instanceField = mw()->fields_manager->instanceField($data['type']);
?>
<div id="mw-custom-fields-address-fields-selector">
 <?php foreach($instanceField->fields as $key=>$value): ?>
 <div>
    <label class="mw-ui-check">
      <input data-for="<?php echo $key; ?>" type="checkbox" value="true" name="options[<?php echo $key; ?>]" <?php if(isset($data['options'][$key])) : ?> checked="checked" <?php endif; ?>  />
      <span></span>
      <span><?php echo $value; ?></span>
     </label>
  </div>
  <?php endforeach; ?>
</div>


<div class="custom-field-settings-values">
  <?php echo $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
