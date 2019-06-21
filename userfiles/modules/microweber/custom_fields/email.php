<?php

$is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

if (!isset($data['input_class']) and isset($params['input-class'])) {
    $data['input_class'] = $params['input-class'];
} elseif (!isset($data['input_class']) and isset($params['input_class'])) {
    $data['input_class'] = $params['input_class'];
} else {
    $data['input_class'] = '';

}


?>

<div class="control-group form-group">
  <label class="mw-ui-label">
  <?php print $data["name"]; ?>
  <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
  <span style="color:red;">*</span>
  <?php endif; ?>
  </label>
   <div class="controls">
    <input type="email" class="<?php print $data['input_class']; ?> mw-ui-field"   <?php if ($is_required): ?> required="true"  <?php endif; ?>
    data-custom-field-id="<?php print $data["id"]; ?>"
    name="<?php print $data["name"]; ?>"

    placeholder="<?php print is_array($data["value"]) ? implode(',', $data["value"]) : $data["value"]; ?>" />
 </div>
</div>