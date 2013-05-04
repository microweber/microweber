<?php

//$rand = rand();


?>

<script>mw.require('forms.js');</script>

<div class="control-group">
  <label class="custom-field-title"><?php print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="number"
        onkeyup="mw.form.typeNumber(this);"
        <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>
        <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["custom_field_name"]; ?>"
         placeholder="<?php print $data["custom_field_value"]; ?>"
        />
  </div>
</div>
