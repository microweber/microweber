<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mw-text-start mb-3">

        <?php if ($settings['show_label']): ?>
            <label class="control-label my-3"><?php echo $data["name"]; ?></label>
        <?php endif; ?>

        <?php $i = 0;
        foreach ($data['values'] as $key => $value): ?>
            <?php $i++; ?>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="<?php echo $data["name_key"]; ?>[]" id="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/>
                <label class="custom-control-label" for="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>"><?php echo $value; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
