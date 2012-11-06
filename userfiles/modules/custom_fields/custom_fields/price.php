<? $rand = rand(); ?>
<? if(!isset($data['make_select'])) : ?> 
<div class="mw-custom-field-group">
  <label class="mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
  
  <? print $data["custom_field_value"]; ?>
    <input type="hidden"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_type"]; ?>" id="custom_field_help_text<? print $rand ?>" value="<? print $data["custom_field_value"]; ?>">
    
    
    
  </div>
</div>
<? else: ?>
 <option   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_type"]; ?>"   value="<? print $data["custom_field_value"]; ?>"><? print $data["custom_field_name"]; ?></option>
<? endif; ?>
