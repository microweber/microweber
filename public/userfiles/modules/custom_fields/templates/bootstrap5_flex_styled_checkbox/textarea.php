<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mb-3 d-flex gap-3 flex-wrap">

        <?php if($settings['show_label']): ?>
        <label class="form-label me-2 align-self-center mb-0 col-xl-4 col-auto">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <textarea type="text" rows="<?php echo $settings['rows']; ?>" cols="<?php echo $settings['cols']; ?>" class="form-control" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" data-custom-field-error-text="<?php echo $data['error_text']; ?>" name="<?php echo $data['name_key']; ?>" placeholder="<?php echo $data['placeholder']; ?>"><?php echo $data['value']; ?></textarea>
        <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
        <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
