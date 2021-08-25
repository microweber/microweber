<?php
$rand = uniqid();
?>
<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
<div class="mw-ui-field-holder">

    <?php if($settings['show_label']): ?>
      <label class="mw-ui-label">
      <?php echo $data["name"]; ?>
      <?php if ($settings['required']): ?>
      <span style="color:red;">*</span>
      <?php endif; ?>
      </label>
    <?php endif; ?>

  <div class="mw-custom-field-form-controls">
    <input type="text" <?php if ($settings['required']): ?> required  <?php endif; ?>  data-custom-field-id="<?php echo $data["id"]; ?>"  name="<?php print $data["name_key"]; ?>" value="<?php echo $data['value']; ?>" id="date_<?php echo $rand; ?>" placeholder="<?php echo $data["placeholder"]; ?>" class="mw-ui-field" />
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
</div>
