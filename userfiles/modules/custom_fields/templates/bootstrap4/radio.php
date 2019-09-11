<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="form-group">
        <label class="col-form-label">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>

        <?php
        $i = 0;
        foreach ($data['values'] as $value):
            $i++;
            ?>
            <label class="mw-ui-check">
                <input type="radio" <?php if ($settings['required'] && $i == 1): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" value="<?php echo $value; ?>" name="<?php echo $data['name']; ?>"
                       <?php if ($data['value'] && $data['value'] == $value): ?>checked="checked"<?php endif; ?> />
                <span></span>
                <span><?php echo $value; ?></span>
            </label>
        <?php endforeach; ?>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>