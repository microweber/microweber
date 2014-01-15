<?php

 $rand = uniqid();


?>
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
  <label ><?php print $data["custom_field_name"]; ?></label>

    <input type="url"
        <?php if ($is_required): ?> required="true"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["custom_field_name"]; ?>"
        id="custom_field_help_text<?php print $rand; ?>"
		  <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?> 
        placeholder="<?php print $data["custom_field_value"]; ?>" />

</div>
