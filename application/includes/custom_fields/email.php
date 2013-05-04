<?php

//$rand = rand();


?>

<div class="control-group">
  <label class="custom-field-title" ><?php print $data["custom_field_name"]; ?></label>
    <input type="email"   <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>
    data-custom-field-id="<?php print $data["id"]; ?>"
    name="<?php print $data["custom_field_name"]; ?>"
     
    placeholder="<?php print $data["custom_field_value"]; ?>" />
</div>
