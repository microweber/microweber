<?

//$rand = rand();


?>

<div class="control-group">
  <label class="custom-field-title" ><? print $data["custom_field_name"]; ?></label>
    <input type="email"   <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>
    data-custom-field-id="<? print $data["id"]; ?>"
    name="<? print $data["custom_field_name"]; ?>"
     
    placeholder="<? print $data["custom_field_value"]; ?>" />
</div>
