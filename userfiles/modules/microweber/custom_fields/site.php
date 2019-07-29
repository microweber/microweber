<?php

 $rand = uniqid();


?>
<?php

$is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

if(!isset($data['input_class'])){
	$data['input_class'] = '';
}

if(isset($data['params']) and isset($data['params']['input_class'])) {
	$data['input_class'] = $data['params']['input_class'];
}
?>

<div class="control-group form-group">
  <label class="mw-ui-label" ><?php print $data["name"]; ?>
	<?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
	<span style="color:red;">*</span>
	<?php endif; ?> 
  </label>
<div class="mw-custom-field-form-controls">
    <input type="url"
        <?php if ($is_required): ?> required="true"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["name"]; ?>"
        id="custom_field_help_text<?php print $rand; ?>"
		class="<?php print $data['input_class']; ?> mw-ui-field"
        placeholder="<?php print $data["value"]; ?>" />

</div>
</div>