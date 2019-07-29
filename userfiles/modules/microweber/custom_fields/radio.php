<?php

//$rand = rand();

if (!isset($data['id'])) { 
include('empty_field_vals.php');
}
?>


<?php
if(!isset($data['input_class'])){
	$data['input_class'] = 'radio-inline';
}

if(isset($data['params']) and isset($data['params']['input_class'])) {
	$data['input_class'] = $data['params']['input_class'];
}

$is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

if (!($data['values'])) {
   $data['values'][0] = _e('Please, set radio options.', true);
}
?>

<?php

//d($data['values']);
//print $data["value"]; ?>
<?php if(is_array($data['values']) and !empty($data['values'])) : ?>

<div class="mw-ui-field-holder">  

<div class="mw-ui-label"><?php print $data["name"]; ?>

	<?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
	<span style="color:red;">*</span>
	<?php endif; ?>
</div>


    <?php $i = 0; foreach($data['values'] as $v):  ?>
      <?php $i++;  $kv =  $v; ?>

  <label class="mw-ui-check">
    <input type="radio" <?php if($is_required and $i==1){ ?> required <?php } ?> name="<?php print $data["name"]; ?>"    data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $kv; ?>" <?php if(isset($data['value']) == true and $data['value'] == $kv): ?> checked="checked" <?php endif; ?> />
    <span></span>
    <span><?php print ($v); ?></span>
  </label>
  <?php endforeach; ?>
</div>
<?php endif; ?>
