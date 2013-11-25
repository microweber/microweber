<?php
//$rand = rand();
if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?php

    $is_required = (isset($data['options']) == true and in_array('required',$data['options']) == true);
       // d($is_required)         ;
          //d($data['options']);
 $skips = array();
 if(isset($params['skip-fields']) and $params['skip-fields'] != ''){
	 $skips = explode(',',$params['skip-fields']);
	  $skips = array_trim($skips);
 }
 
 
?>



<?php
if(!is_array($data['custom_field_values'])){
	$default_data = array('country'=>'Country','city'=>'City', 'zip'=>'Zip', 'state'=>'State', 'address'=>'Address');
	$data['custom_field_values'] = $default_data;
}


if (!isset( $data['input_class']) and isset($params['input-class'])) {
     $data['input_class'] = $params['input-class'];
} elseif (!isset( $data['input_class']) and  isset($params['input_class'])) {
     $data['input_class'] = $params['input_class'];
} else {
	$data['input_class'] = 'form-control';
	
}
 
//print $data["custom_field_value"]; ?>
<?php if(is_array($data['custom_field_values'])) : ?>

<div class="control-group form-group">
  <label class="mw-ui-label mw-custom-field-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <?php print $data['custom_field_name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>
  <hr style="margin-top: 7px;" />
  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>


   
   
    <?php foreach($data['custom_field_values'] as $k=>$v): ?>
    <?php if((!in_array($data['custom_field_name'], $skips) and !in_array($k, $skips)) and (!isset($data['options']) or in_array($k,$data['options']) == true)) : ?>
    <?php if(is_string( $v)){
	$kv =  $v;
	} else {
	$kv =  $v[0];	
	}
	if($kv == ''){
	$kv = ucwords($k);		
	}
 
 
	
	?>
     <label><?php print ($kv); ?></label>

     <input type="text"  <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>   name="<?php print $data['custom_field_name'] ?>[<?php print ($k); ?>]" <?php if($is_required){ ?> required <?php } ?>  data-custom-field-id="<?php print $data["id"]; ?>"  />
     <?php endif; ?>
    <?php endforeach; ?>

</div>
<hr />
<?php endif; ?>
