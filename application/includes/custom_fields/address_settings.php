<? include('settings_header.php'); ?>
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
  <div class="custom-field-col-left">
    <div class="mw-custom-field-group ">
      <label class="mw-ui-label" for="input_field_label<? print $rand; ?>">
        <?php _e('Define Title'); ?>
      </label>
      <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand; ?>">
      <div class="vSpace"></div>
      <?php

     $opt = array(
        'country'=>'Country',
        'city'=>'City',
        'addr'=>'Address',
        'state'=>'State/Province',
        'zip'=>'Zip/Postal Code'
     );
	 
	// d($data['options'])

      ?>
      <div id="mw-custom-fields-address-fields-selector">
        <?php foreach($opt as $key => $val){ ?>
        <div>
          <label class="mw-ui-check">
            <input data-for="<?php print $key; ?>" type="checkbox" value="<?php print $key; ?>" name="options[]" <? if(in_array( $key,$data['options'])) : ?> checked="checked" <? endif; ?>  />
            <span></span><span><?php print $val; ?></span></label>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="custom-field-col-right">
    <div class="mw-custom-field-group">
     
    
    
      <?php foreach($opt as $key => $val){ ?>
      <div id="mw-custom-fields-address-fields-<?php print $key; ?>">
        <label class="mw-ui-label"><?php print $val; ?></label>
        <input type="text" class="mw-ui-field" name="custom_field_value[<?php print $key; ?>]" <? if(isset($data['custom_field_values'][$key]) and isset($data['custom_field_values'][$key][0])) : ?> value="<? print $data['custom_field_values'][$key][0] ?>"  <? endif; ?> />
      </div>
      <?php } ?>
    </div>


    <?php print $savebtn; ?>
    </div>
  <? include('settings_footer.php'); ?>
