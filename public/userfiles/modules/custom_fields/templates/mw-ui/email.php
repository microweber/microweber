<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
    <div class="mw-ui-field-holder">

        <?php if($settings['show_label']): ?>
        <label class="mw-ui-label">
        <?php echo $data['name']; ?>
        <?php if ($settings['required']): ?>
        <span style="color: red;">*</span>
        <?php endif; ?>
        </label>
        <?php endif; ?>

        <div class="mw-ui-controls">
            <input type="email" class="mw-ui-field" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" data-custom-field-error-text="<?php echo $data['error_text']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>" placeholder="<?php echo $data['placeholder']; ?>" />
        </div>
    </div>
</div>
