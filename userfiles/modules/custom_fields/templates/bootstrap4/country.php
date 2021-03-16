<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="form-group edit">

        <?php if($settings['show_label']): ?>
            <label class="control-label text-left edit">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

            <div class="form-group edit">
                <select class="form-control edit">
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
