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
  <label class="mw-ui-label" ><?php print $data["name"]; ?></label>

    <input type="url"
        <?php if ($is_required): ?> required="true"  <?php endif; ?>
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["name"]; ?>"
        id="custom_field_help_text<?php print $rand; ?>"
		class="mw-ui-field"
        placeholder="<?php print $data["value"]; ?>" />

</div>
