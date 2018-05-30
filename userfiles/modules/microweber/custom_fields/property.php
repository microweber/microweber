<?php

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);
if (!isset( $data['input_class']) and isset($params['input-class'])) {
     $data['input_class'] = $params['input-class'];
} elseif (!isset( $data['input_class']) and  isset($params['input_class'])) {
     $data['input_class'] = $params['input_class'];
} else {
	$data['input_class'] = 'form-control';
	
}




?>

<div class="control-group form-group">
  <label class="mw-ui-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print ucwords(str_replace('_', ' ', $data['name'])); ?>
    <?php elseif(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>
  <input type="hidden"
  		class="mw-ui-field"
        name="<?php print $data["name"]; ?>"
        value="<?php print $data["value"]; ?>"
  		data-custom-field-id="<?php print $data["id"]; ?>" />
  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>
   
  <div class="controls">
      <?php if(is_array($data["value"])): ?>
          <?php print implode(',',$data["value"]); ?>

      <?php else : ?>
          <?php print $data["value"]; ?>
      <?php endif; ?>
  </div>
  
</div>
