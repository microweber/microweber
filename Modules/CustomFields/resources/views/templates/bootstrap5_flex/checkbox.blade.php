<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mb-3 d-flex gap-3 flex-wrap">

        <?php if ($settings['show_label']): ?>
            <label class="form-label my-3 "><?php echo $data["name"]; ?></label>
        <?php endif; ?>

        <?php $i = 0;
        foreach ($data['values'] as $key => $value): ?>
            <?php $i++; ?>
            <div class="custom-control custom-checkbox my-2">
                <input class="form-check-input" type="checkbox" name="<?php echo $data["name_key"]; ?>[]" id="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/>
                <label class="custom-control-label" for="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>"><?php echo $value; ?>



                    <?php if(isset($data['values_price_modifiers']) and !empty($data['values_price_modifiers']) and isset($data['values_price_modifiers'][$key]) and $data['values_price_modifiers'][$key]) : ?>
                        (+<?php echo currency_format($data['values_price_modifiers'][$key]); ?>)
                    <?php endif; ?>

                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
