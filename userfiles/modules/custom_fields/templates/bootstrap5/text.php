<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mw-text-start mb-3">


        <?php if($settings['show_label']): ?>
            <label class="control-label my-3">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <?php if ($settings['as_text_area']): ?>
            <textarea type="text" class="form-control" rows="<?php echo $settings['rows']; ?>" cols="<?php echo $settings['cols']; ?>" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" data-custom-field-error-text="<?php echo $data['error_text']; ?>" name="<?php echo $data['name_key']; ?>" placeholder="<?php echo $data['placeholder']; ?>"><?php echo $data['value']; ?></textarea>
        <?php else: ?>
            <input type="text" class="form-control" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" data-custom-field-error-text="<?php echo $data['error_text']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>" placeholder="<?php echo $data['placeholder']; ?>"/>
        <?php endif; ?>
        <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
        <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
