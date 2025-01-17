<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="d-flex flex-column my-2">
        <?php if($settings['show_label']): ?>
            <label class="form-label my-3">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <div class="d-flex align-items-center">
            <input type="color" 
                   class="form-control form-control-color" 
                   <?php if ($settings['required']): ?>required<?php endif; ?>
                   data-custom-field-id="<?php echo $data['id']; ?>"
                   data-custom-field-error-text="<?php echo $data['error_text']; ?>"
                   name="<?php echo $data['name_key']; ?>"
                   value="<?php echo $data['value']; ?>"
                   placeholder="<?php echo $data['placeholder']; ?>"
            />
        </div>

        <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
        <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>

        <?php if ($data['help']): ?>
            <div class="form-text mt-1"><?php echo $data['help']; ?></div>
        <?php endif; ?>
    </div>
</div>
