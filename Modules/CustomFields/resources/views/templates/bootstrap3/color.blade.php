<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="form-group">
        <?php if($settings['show_label']): ?>
            <label class="control-label">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <input type="color" 
               class="form-control" 
               <?php if ($settings['required']): ?>required<?php endif; ?>
               data-custom-field-id="<?php echo $data['id']; ?>"
               data-custom-field-error-text="<?php echo $data['error_text']; ?>"
               name="<?php echo $data['name_key']; ?>"
               value="<?php echo $data['value']; ?>"
               placeholder="<?php echo $data['placeholder']; ?>"
        />

        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>
    </div>
</div>
