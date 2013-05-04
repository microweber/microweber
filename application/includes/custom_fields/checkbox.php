<?php

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?php

  ?>
<?php if(!empty($data['custom_field_values'])) : ?>
 <div class="custom-field-title"><?php print $data["custom_field_name"]; ?></div>
<div class="control-group custom-fields-type-checkbox">

  <div class="mw-customfields-checkboxes">

  <?php foreach($data['custom_field_values'] as $v): ?>

    <label class="checkbox">
        <input type="checkbox" name="<?php print $data["custom_field_name"]; ?>[]" id="field-<?php print $data["id"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $v; ?>" />
        <span><?php print ($v); ?></span>
    </label>

  <?php endforeach; ?>

  </div>
</div>

<?php endif; ?>
