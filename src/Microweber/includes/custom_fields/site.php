<?php

//$rand = rand();


?>
<?php

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

?>

<div class="control-group">
  <label ><?php print $data["custom_field_name"]; ?></label>

    <input type="url"
        <?php if ($is_required): ?> required="true"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["custom_field_name"]; ?>"
        id="custom_field_help_text<?php print $rand; ?>"
        placeholder="<?php print $data["custom_field_value"]; ?>" />

</div>
