<?php
// var_dump($data);var_dump($settings);die();
?>
<div class="col-md-<?php echo $settings['field_size']; ?>">
    <?php foreach($data['values'] as $key=>$value): ?>
        <?php if($settings['show_label']): ?>
            <label class="col-form-label" for="inputDefault"><?php _e($value); ?></label>
        <?php endif; ?>
        <?php if ($key == 'address')  : ?>
            <?php if ($data['countries']) { ?>
                <div class="mw-text-start mb-3">
                    <select class="form-control">
                        <option><?php _e('Choose address') ?></option>
                        <option><?php foreach ($data['countries'] as $country): ?>
                        <option value="<?php echo $country ?>"><?php echo $country ?></option>
                        <?php endforeach; ?></option>
                    </select>
                </div>
            <?php } else { ?>
                <input type="text" class="mw-ui-field" name="<?php echo $data['name'] ?>[<?php echo ($key); ?>]" <?php if ($settings['required']) { ?> required <?php } ?> data-custom-field-id="<?php echo $data["id"]; ?>"
            <?php } ?>

        <?php else: ?>
            <div class="mw-text-start mb-3">
                <input type="text" class="form-control" name="<?php echo $data['name'] ?>[<?php echo ($key); ?>]" <?php if ($settings['required']) { ?> required <?php } ?> data-custom-field-id="<?php echo $data["id"]; ?>" placeholder="" id="inputDefault">
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
