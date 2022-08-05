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

        <input type="hidden" class="form-control" data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>"/>

        <div class="controls">
            <?php echo $data["value"]; ?>
        </div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
