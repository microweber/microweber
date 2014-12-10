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
	$data['input_class'] = 'radio-inline';
	
}

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

?>

<?php

//d($data['values']);
//print $data["value"]; ?>
<?php if(is_array($data['values']) and !empty($data['values'])) : ?>

<div class="mw-ui-field-holder">  

<div class="mw-ui-label"><?php print $data["name"]; ?></div>


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
