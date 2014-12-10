<?php

//$rand = rand();


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

<script>mw.require('forms.js');</script>

<div class="control-group form-group">
  <label class="mw-ui-label"><?php print $data["name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="number"
        onkeyup="mw.form.typeNumber(this);"
        <?php if ($is_required): ?> required="true"  <?php endif; ?>
        class="mw-ui-field"
        data-custom-field-id="<?php print $data["id"]; ?>"
        name="<?php print $data["name"]; ?>"
         placeholder="<?php print $data["value"]; ?>"
        />
  </div>
</div>
