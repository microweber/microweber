<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mb-3 d-flex">

        <?php if($settings['show_label']): ?>
            <label class="control-label me-2 align-self-center mb-0">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

            <div class="mb-3 d-flex">
                <select class="form-control">
                    <option><?php _e($data['placeholder']) ?></option>
                    <option><?php foreach ($data['values'] as $country): ?>
                    <option value="<?php echo $country ?>"><?php echo $country ?></option>
                    <?php endforeach; ?></option>
                </select>
            </div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
