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
  <label class="mw-ui-label"><?php print $data["custom_field_name"]; ?></label>
  <div class="mw-custom-field-form-controls">
    <input type="text"    <?php if ($is_required): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["custom_field_name"]; ?>" id="date_<?php print $rand; ?>" placeholder="<?php print $data["custom_field_value"]; ?>"    class="mw-ui-field">
  </div>
</div>


 <script>
    mw.require("datepicker.css", true);
    mw.require("datepicker.js", true);
 </script>
 <script>
    $(document).ready(function(){
      mw.$( "#date_<?php print $rand; ?>" ).datepicker();
    });
 </script>
