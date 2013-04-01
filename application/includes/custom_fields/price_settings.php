<? include('settings_header.php'); ?>
<div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
    <label class="mw-ui-label" for="input_field_label<? print $rand; ?>">
      <?php _e('Define Title'); ?>
    </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand; ?>">

  </div>
</div>



<div class="custom-field-col-right">
  <div class="mw-custom-field-group">
    <label class="mw-ui-label" for="custom_field_value<? print $rand; ?>">Value</label>
    <b><? print curency_symbol($curr=false,$key=3); ?> </b>
    <input type="text"
    class="mw-ui-field"
    name="custom_field_value"
    value="<? print ($data['custom_field_value']) ?>" />



    <label class="mw-ui-label" >Old price</label>



 <input type="text"  class="mw-custom-field-option" name="options[old_price]"  <? if(isset($data['options']) == true and isset($data['options']["old_price"]) == true): ?> value="<?php print $data['options']["old_price"][0]; ?>"  <? endif; ?>  >




  </div> <?php print $savebtn; ?>
</div>
<? include('settings_footer.php'); ?>
