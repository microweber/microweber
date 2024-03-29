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

        <input type="hidden" class="form-control" data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>"/>

        <div class="controls">
            <?php echo $data["value"]; ?>
        </div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
