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
} else {
	$data['input_class'] = 'form-control';
	
}




//print $data["value"]; ?>
<?php if(!empty($data['values'])) : ?>
<div class="mw-ui-field-holder">    
<label class="mw-ui-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>



  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>
 


 <?php if(is_array($data['values'])): ?>
  <select <?php if(isset($data['options']) and is_array($data['options']) == true and  in_array('multiple', $data['options'])): ?> multiple="multiple"<?php endif; ?> class="mw-ui-field"  name="<?php print $data["name"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>">
    <?php
	foreach($data['values'] as $k=>$v): ?>
    <?php if(is_string($k)){
	$kv =  $k;
	} else {
	$kv =  $v;
	}
	
	 
	
	?>
    <option  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $kv; ?>" 
	<?php if(isset($data['value']) == true and $data['value'] == $kv): ?> selected="selected" <?php endif; ?> >
 
	<?php print ($v); ?></option>
    <?php endforeach; ?>
  </select>
  
  <?php endif; ?>
</div>
<?php endif; ?>
