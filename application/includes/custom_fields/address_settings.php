<? include('settings_header.php'); ?>


<script>
if(!!mw.custom_fields.address_settings){
    mw.custom_fields.address_settings = {

    };
}

mw.$("#mw-custom-fields-address-fields-selector input").commuter(function(){
  var f = $(this).dataset('for');
  mw.$('#mw-custom-fields-address-fields-'+f).slideDown('fast');

}, function(){
  var f = $(this).dataset('for');
  mw.$('#mw-custom-fields-address-fields-'+f).slideUp('fast');
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
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">

    <div class="vSpace"></div>




    <div id="mw-custom-fields-address-fields-selector">
        <div><label class="mw-ui-check"><input data-for="country" type="checkbox" checked="checked"><span></span><span>Country</span></label></div>
        <div><label class="mw-ui-check"><input data-for="city" type="checkbox" checked="checked"><span></span><span>City</span></label></div>
        <div><label class="mw-ui-check"><input data-for="addr" type="checkbox" checked="checked"><span></span><span>Address 1</span></label></div>
        <div><label class="mw-ui-check"><input data-for="state" type="checkbox" checked="checked"><span></span><span>State/Province</span></label></div>
        <div><label class="mw-ui-check"><input data-for="zip" type="checkbox" checked="checked"><span></span><span>Zip/Postal Code</span></label></div>
    </div>




</div>
</div>



   <div class="custom-field-col-right">
    <div class="mw-custom-field-group">


   <div id="mw-custom-fields-address-fields-country">
        <label class="mw-ui-label">Country</label>
        <input type="text" class="mw-ui-field" name="custom_field_value[country]"  />
    </div>

    <div id="mw-custom-fields-address-fields-city">
        <label class="mw-ui-label">City</label>
        <input type="text" class="mw-ui-field" name="custom_field_value[city]" />
    </div>

    <div id="mw-custom-fields-address-fields-addr">
        <label class="mw-ui-label">Address</label>
        <input type="text" class="mw-ui-field" name="custom_field_value[address]" />
    </div>

    <div id="mw-custom-fields-address-fields-state">
        <label class="mw-ui-label">State/Province</label>
        <input type="text" class="mw-ui-field" name="custom_field_value[state]" />
    </div>

    <div id="mw-custom-fields-address-fields-zip">
        <label class="mw-ui-label">Zip/Postal Code</label>
        <input type="text" class="mw-ui-field" name="custom_field_value[zip]" />
    </div>



    </div>
    <?php print $savebtn; ?>
    </div>
<? include('settings_footer.php'); ?>
