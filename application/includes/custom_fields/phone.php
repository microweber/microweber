<?php

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>


<?php

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

?>


<?php


//print $data["custom_field_value"]; ?>
<div class="control-group">
<label class="custom-field-title">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print ucwords(str_replace('_', ' ', $data['name'])); ?>
    <?php elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <?php print $data['custom_field_name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>
<input type="text"
         <?php if ($is_required): ?> required="true"  <?php endif; ?>
        <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["custom_field_name"]; ?>"
        placeholder="<?php print $data["custom_field_value"]; ?>" />
        </div>
