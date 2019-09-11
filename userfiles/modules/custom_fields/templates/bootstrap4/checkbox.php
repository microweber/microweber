<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="mw-ui-field-holder custom-fields-type-checkbox">
        <div class="col-form-label"><?php echo $data["name"]; ?></div>
        <div class="form-group">
            <?php $i = 0;
            foreach ($data['values'] as $key => $value): ?>
                <?php $i++; ?>
                <div class="custom-control custom-checkbox">
                    <input class="form-control" type="checkbox" name="<?php echo $data["name"]; ?>[]" id="field-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/>
                    <label class="custom-control-label" for="field-<?php echo $data["id"]; ?>"><?php echo $value; ?></label>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>