<?  // d($data); ?>

<div class="control-group">
  <label>
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print ucwords(str_replace('_', ' ', $data['name'])); ?>
    <? elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <? print $data['custom_field_name'] ?>
    <? else : ?>
    <? endif; ?>
  </label>
  <? if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><? print $data['help'] ?></small>
  <? endif; ?>
  <? if(isset($data['options']) == true and isset($data['options']["as_text_area"]) == true): ?>
  <div class="controls">
    <textarea <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>   data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>" placeholder="<? print $data["custom_field_value"]; ?>">
    </textarea>
  </div>
  <? else : ?>
  <div class="controls">
    <input type="text"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>   data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>"  placeholder="<? print $data["custom_field_value"]; ?>" />
  </div>
  <? endif; ?>
</div>
