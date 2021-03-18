<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if ($settings['show_label']): ?>
            <label class="control-label">
                <?php echo $data['name']; ?>
                <?php if ($settings['required']): ?>
                    <span style="color: red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <?php
        $i = 0;
        foreach ($data['values'] as $key => $value):
            $i++;
            ?>
            <div class="custom-control custom-radio d-inline-block">
                <input type="radio" id="custom-radio-<?php echo $data['id'] . '-' . $key; ?>" class="custom-control-input" <?php if ($settings['required'] && $i == 1): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" value="<?php echo $value; ?>"
                       name="<?php echo $data['name']; ?>">
                <label class="custom-control-label" for="custom-radio-<?php echo $data['id'] . '-' . $key; ?>"><?php echo $value; ?></label>
            </div>
        <?php endforeach; ?>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>

    </div>
</div>