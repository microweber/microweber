<div class="mw-custom-field-group">
 <?  // d($data); ?>
  <label class="mw-custom-field-label">
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print ucwords(str_replace('_', ' ', $data['name'])); ?>
    <? elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <? print $data['custom_field_name'] ?>
    <? else : ?>
    <? endif; ?>
  </label>
  <? if(isset($data['help']) == true and $data['help'] != ''): ?>
  <br />
  <small  class="mw-custom-field-help"><? print $data['help'] ?></small>
  <? endif; ?>
  <div class="mw-custom-field-form-controls">
   <? if(isset($data['options']) == true and isset($data['options']["as_text_area"]) == true and $data['options']["as_text_area"] != ''): ?>
   
   
   <textarea <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>   data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>"><? print $data["custom_field_value"]; ?></textarea>
 
    
    <? else : ?>
     <input type="text"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>   data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>"  value="<? print $data["custom_field_value"]; ?>">
    
     <? endif; ?>
  </div>
</div>
