<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if($settings['show_label']): ?>
        <label class="control-label"><?php echo $data["name"]; ?></label>
        <?php endif; ?>

        <div class="mw-customfields-checkboxes">
            <?php $i = 0;
            foreach ($data['values'] as $value): ?>
                <?php $i++; ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="<?php echo $data["name_key"]; ?>[]" id="field-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/> <?php echo $value; ?>
                    </label>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>
