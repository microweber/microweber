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
<?php if(!empty($data['custom_field_values'])) : ?>
<div class="custom-field-title"><?php print $data["custom_field_name"]; ?></div>
<div class="control-group custom-fields-type-radio">

    <?php $i = 0; foreach($data['custom_field_values'] as $v):  ?>
      <?php $i++; ?>
  <?php
  $kv =  $v;	
  
   /*if(is_string( $k)){
	$kv =  $k;
	} else {
	$kv =  $v;	
	}*/
	?>
  <label class="radio">
    <input type="radio" <?php if($is_required and $i==1){ ?> required <?php } ?> name="<?php print $data["custom_field_name"]; ?>"  <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>   data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $kv; ?>" <?php if(isset($data['custom_field_value']) == true and $data['custom_field_value'] == $kv): ?> checked="checked" <?php endif; ?> />
    <span><?php print ($v); ?></span>
  </label>
  <?php endforeach; ?>
</div>
<?php endif; ?>
