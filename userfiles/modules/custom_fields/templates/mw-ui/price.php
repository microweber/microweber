<?php $rand = rand(); ?>

<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
<?php if ($settings['make_select']) : ?>
<option type="url" class="mw-ui-field <?php echo $settings['class']; ?>" id="custom_field_help_text<?php echo $rand; ?>" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" value="<?php echo $data['value']; ?>"></option>
<?php else: ?>
<div class="mw-custom-field-group mw-custom-field-price">
  <label class="mw-custom-field-label" ><?php echo $data["name"]; ?></label>
  <div class="mw-custom-field-form-controls">
  
  <?php echo $data["value"]; ?>
    <input type="hidden" <?php if ($settings['required']): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php echo $data["id"]; ?>"  name="<?php echo $data["name"]; ?>" id="custom_field_help_text<?php echo $rand; ?>" value="<?php echo $data["value"]; ?>">
   
    <?php if ($data['options']["old_price"]): ?> <span style="text-decoration: line-through"><?php echo $data['options']["old_price"][0]; ?></span>  <?php endif; ?>

  </div>
</div>
<?php endif; ?>
</div>