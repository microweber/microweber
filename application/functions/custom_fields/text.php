<?
 //p($data);
$rand = rand();
 ?>

<div class="control-group">
  <label class="control-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></label>
  <div class="controls">
    <input type="text"   <? if(trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>  name="<? print $data["custom_field_name"]; ?>" id="custom_field_help_text<? print $rand ?>" value="<? print $data["custom_field_value"]; ?>">
  </div>
</div>

