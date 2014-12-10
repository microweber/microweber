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
<?php $is_required = (isset($data['options']) == true and is_array($data['options']) == true and in_array('required',$data['options']) == true);


  
          ?>
<div class="custom-field-settings-name">
  <div class="mw-custom-field-group ">
    <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
      <?php _e('Title'); ?>
    </label>
    <input type="text" class="mw-ui-field" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
    <?php

     $opt = array(
        'country'=>'Country',
        'city'=>'City',
        'address'=>'Address',
        'state'=>'State/Province',
        'zip'=>'Zip/Postal Code'
     );

	
     ?>
    <div id="mw-custom-fields-address-fields-selector">
      <?php if(is_array($opt)) { foreach($opt as $key => $val){ ?>
      <div>
        <label class="mw-ui-check">
          <input data-for="<?php print $key; ?>" type="checkbox" value="<?php print $key; ?>" name="options[]" <?php if(isset($data['options']) and is_array($data['options']) and in_array( $key,$data['options']) or empty($data['options'])) : ?> checked="checked" <?php endif; ?>  />
          <span></span><span><?php print $val; ?></span></label>
      </div>
      <?php
		    }
		 } ?>
    </div>
  </div>
</div>
<div class="custom-field-settings-values">
  <div class="mw-custom-field-group">
    <?php foreach($opt as $key => $val){ ?>
    <div class="mw-ui-field-holder mw-custom-fields-address-fields-<?php print $key; ?>">
      <label class="mw-ui-label"><?php print $val; ?></label>
      <input type="text" class="mw-ui-field" name="value[<?php print $key; ?>]" <?php if(isset($data['values'][$key]) and isset($data['values'][$key][0])) : ?> value="<?php print $data['values'][$key][0] ?>"  <?php endif; ?> />
    </div>
    <?php } ?>
  </div>
  <hr>
  <label class="mw-ui-check">
    <input type="checkbox"  class="mw-custom-field-option" name="options[]"  <?php if($is_required == true): ?> checked="checked" <?php endif; ?> value="required">
    <span></span><span>
    <?php _e("Required"); ?>
    ?</span></label>
  <?php print $savebtn; ?> </div>
<?php include('settings_footer.php'); ?>
