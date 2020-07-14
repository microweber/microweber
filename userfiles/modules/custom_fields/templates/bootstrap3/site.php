<?php
$rand = uniqid();
?>
<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if($settings['show_label']): ?>
        <label class="control-label">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <?php if ($data['help']): ?>
            <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
        <?php endif; ?>
        <input type="url" class="mw-ui-field form-control" id="custom_field_help_text<?php print $rand; ?>" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" data-custom-field-error-text="<?php echo $data['error_text']; ?>" value="<?php echo $data['value']; ?>" name="<?php echo $data['name']; ?>"
               placeholder="<?php echo $data['placeholder']; ?>"/>
    </div>
</div>
