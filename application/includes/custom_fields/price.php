<? //$rand = rand(); ?>
<? if(!isset($data['make_select'])) : ?> 
<div class="mw-custom-field-group">
  <label class="mw-custom-field-label" ><? print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
  
  <? print $data["custom_field_value"]; ?>
    <input type="hidden"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_type"]; ?>" id="custom_field_help_text<? print $rand; ?>" value="<? print $data["custom_field_value"]; ?>">
    
    <? if(isset($data['options']) == true and isset($data['options']["old_price"]) == true): ?> <span style="text-decoration: line-through"><?php print $data['options']["old_price"][0]; ?></span>  <? endif; ?>

  </div>
</div>
<? else: ?>
 <option   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_type"]; ?>"   value="<? print $data["custom_field_value"]; ?>"><? print $data["custom_field_name"]; ?></option>
<? endif; ?>
