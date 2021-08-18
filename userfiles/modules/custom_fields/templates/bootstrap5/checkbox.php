<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="text-start mb-3">

        <?php if ($settings['show_label']): ?>
            <div class="control-label mb-3"><?php echo $data["name"]; ?></div>
        <?php endif; ?>

        <?php $i = 0;
        foreach ($data['values'] as $key => $value): ?>
            <?php $i++; ?>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="<?php echo $data["name"]; ?>[]" id="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/>
                <label class="custom-control-label" for="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>"><?php echo $value; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
