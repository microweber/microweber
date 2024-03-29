<style>
    .form-selectgroup-item {
        padding: 30px;
        height: 100%;
        display: grid;
        align-items: center;
        justify-content: center;

        border-radius: var(--mw-form-control-border-radius);
        border-color: var(--mw-form-control-border-color);
        border-width: var(--mw-form-control-border-size);
        border-style: var(--mw-form-control-border-style);
        background-color: var(--mw-form-control-background);
        padding-block: var(--mw-form-control-padding-block);
        padding-inline: var(--mw-form-control-padding-inline);
        color: var(--mw-form-control-text-color);
    }

    .form-selectgroup-item:has(input:checked) {
        border-color: var(--mw-primary-color);
        background-color: transparent;
    }


    .form-selectgroup-item:hover {
        border-color: var(--mw-primary-color);
        background-color: transparent;
    }
</style>


<div class="mb-3 row col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <?php if ($settings['show_label']): ?>
        <label class="form-label my-3 "><?php echo $data["name"]; ?></label>
    <?php endif; ?>

    <?php $i = 0;
    foreach ($data['values'] as $key => $value): ?>
        <?php $i++; ?>
        <div class="col-lg-3 col-6 form-selectgroup form-selectgroup-pills my-3">

                <label class="form-selectgroup-item" for="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>">
                     <input class="form-selectgroup-input mb-3" type="checkbox" name="<?php echo $data["name_key"]; ?>[]" id="field-<?php echo $i; ?>-<?php echo $data["id"]; ?>" data-custom-field-id="<?php echo $data["id"]; ?>" value="<?php echo $value; ?>"/>
                    <span class="form-selectgroup-label"><?php echo $value; ?></span>

                    <?php if(isset($data['values_price_modifiers']) and !empty($data['values_price_modifiers']) and isset($data['values_price_modifiers'][$key]) and $data['values_price_modifiers'][$key]) : ?>
                        (+<?php echo currency_format($data['values_price_modifiers'][$key]); ?>)
                    <?php endif; ?>

                </label>
        </div>
    <?php endforeach; ?>

</div>

