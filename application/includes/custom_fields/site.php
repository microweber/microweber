<?

//$rand = rand();


?>

<div class="control-group">
  <label ><?php print $data["custom_field_name"]; ?></label>

    <input type="text"
        <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["custom_field_name"]; ?>"
        id="custom_field_help_text<?php print $rand; ?>"
        placeholder="<?php print $data["custom_field_value"]; ?>" />

</div>
