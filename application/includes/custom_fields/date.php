<?php

$rand = uniqid();


?>

<div class="control-group">
  <label class="custom-field-title"><?php print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="text"   <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["custom_field_name"]; ?>" id="date_<?php print $rand; ?>" placeholder="<?php print $data["custom_field_value"]; ?>">
  </div>
</div>


 <script>
    mw.require("datepicker.css", true);
    mw.require("datepicker.js", true);
 </script>
 <script>
    $(document).ready(function(){
      mw.$( "#date_<?php print $rand; ?>" ).datepicker();

    });
 </script>
