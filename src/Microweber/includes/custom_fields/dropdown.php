<?php
 
        

//$rand = rand();
if (!isset($data['id'])) {
 include('empty_field_vals.php');
}
?>
<?php

if (!isset( $data['input_class']) and isset($params['input-class'])) {
     $data['input_class'] = $params['input-class'];
} elseif (!isset( $data['input_class']) and  isset($params['input_class'])) {
     $data['input_class'] = $params['input_class'];
}


   //d($data);

//print $data["custom_field_value"]; ?>
<?php if(!empty($data['custom_field_values'])) : ?>

<label class="custom-field-title">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <?php print $data['custom_field_name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>

<div class="control-group">

  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>



 <?php if(is_array($data['custom_field_values'])): ?>
  <select <?php if(isset($data['options']) and is_array($data['options']) == true and  in_array('multiple', $data['options'])): ?> multiple="multiple"<?php endif; ?> <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>  name="<?php print $data["custom_field_name"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>">
    <?php
	foreach($data['custom_field_values'] as $k=>$v): ?>
    <?php if(is_string( $k)){
	$kv =  $k;
	} else {
	$kv =  $v;
	}
	?>
    <option  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $kv; ?>" <?php if(isset($data['custom_field_value']) == true and $data['custom_field_value'] == $kv): ?> selected="selected" <?php endif; ?> ><?php print ($v); ?></option>
    <?php endforeach; ?>
  </select>
  
  <?php endif; ?>
</div>
<?php endif; ?>
