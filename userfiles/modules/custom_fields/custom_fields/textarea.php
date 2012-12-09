<div class="mw-custom-field-group">
  <label class="mw-custom-field-label">
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print $data['name'] ?>
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
    <textarea  <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>" ><? print $data["custom_field_value"]; ?></textarea>
  </div>
</div>
