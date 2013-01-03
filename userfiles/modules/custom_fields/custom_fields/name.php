<?

$rand = rand();


?>

<div class="mw-custom-field-group">
  <label class="mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="text"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>  data-custom-field-id="<? print $data["id"]; ?>"  name="<? print $data["custom_field_name"]; ?>" id="custom_field_help_text<? print $rand ?>" value="<? print $data["custom_field_value"]; ?>">
  </div>
</div>
