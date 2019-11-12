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
  margin-right: 6px;
  top: 3px;
}
</style>
<?php 
//  var_dump($data['values']);var_dump($settings);die();
?>
<div class="custom-field-settings-name">
  <div class="mw-custom-field-group ">
    <label class="mw-ui-label" for="input_field_label<?php echo $rand; ?>">
      <?php _e('Title'); ?>
    </label>
    <input type="text" class="mw-ui-field mw-full-width" value="<?php echo ($data['name']) ?>" name="name" id="input_field_label<?php echo $rand; ?>">
    
    <div id="mw-custom-fields-address-fields-selector">
    
     <?php foreach($data['default_address_fields'] as $key=>$value): ?>
      <div>
        <label class="mw-ui-check">
          <input data-for="<?php echo $key; ?>" type="checkbox" value="true" name="options[<?php echo $key; ?>]" <?php if(isset($data['values'][$key])) : ?> checked="checked" <?php endif; ?>  />
          <span></span>
          <span><?php echo $value; ?></span>
         </label> 
      </div>
      <?php endforeach; ?> 
    </div>
  </div>
</div>

<div class="custom-field-settings-values">
  <?php 
  
  /*<div class="mw-custom-field-group">
    <?php foreach($opt as $key => $val){ ?>
    <div class="mw-ui-field-holder mw-custom-fields-address-fields-<?php echo $key; ?>">
      <label class="mw-ui-label"><?php echo $val; ?></label>
      <input type="text" class="mw-ui-field" name="value[<?php echo $key; ?>]" <?php if(isset($data['values'][$key]) and isset($data['values'][$key])) : ?> value="<?php echo $data['values'][$key] ?>"  <?php endif; ?> />
    </div>
    <?php } ?>
  </div>*/
  
  ?>
  <hr>
  <label class="mw-ui-check">
    <input type="checkbox"  class="mw-custom-field-option" name="options[required]" <?php if ($settings['required']): ?> checked="checked" <?php endif; ?> value="true">
    <span></span><span>
    <?php _e("Required"); ?>
    ?</span></label>
    <br><br>
  <?php echo $savebtn; ?>
</div>


<div class="mw-custom-field-group">
    <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<?php include('settings_footer.php'); ?>
