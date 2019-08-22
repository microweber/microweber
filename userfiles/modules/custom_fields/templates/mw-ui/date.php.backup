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
  <label class="mw-ui-label"><?php print $data["name"]; ?>
  <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
  <span style="color:red;">*</span>
  <?php endif; ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" <?php if ($is_required): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["name"]; ?>" id="date_<?php print $rand; ?>" placeholder="<?php print $data["value"]; ?>"    class="<?php print $data['input_class']; ?> mw-ui-field">
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
