<?php $rand = rand(); ?>

<?php if(!isset($data['make_select'])) : ?> 
<div class="mw-custom-field-group mw-custom-field-price">
  <label class="mw-custom-field-label" ><?php print $data["name"]; ?></label>
  <div class="mw-custom-field-form-controls">
  
  <?php print $data["value"]; ?>
    <input type="hidden" <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["type"]; ?>" id="custom_field_help_text<?php print $rand; ?>" value="<?php print $data["value"]; ?>">
   
    <?php if(isset($data['options']) == true and isset($data['options']["old_price"]) == true): ?> <span style="text-decoration: line-through"><?php print $data['options']["old_price"][0]; ?></span>  <?php endif; ?>

  </div>
</div>
<?php else: ?>
<option type="url" class="mw-ui-field <?php echo $settings['class']; ?>" id="custom_field_help_text<?php print $rand; ?>" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" value="<?php echo $data['value']; ?>"></option>
<?php endif; ?>