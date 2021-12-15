<div class="<?php print $config['module_class'] ?>">
    <?php if(!$disable_default_shipping_fields) :?>
        <div class="mw-ui-field-holder" id="<?php print $rand; ?>">
            <?php $selected_country = mw()->user_manager->session_get('shipping_country'); ?>

            <label class="mw-ui-label"><?php _e("Choose country"); ?></label>
            <select name="country" class="mw-ui-field mw-full-width">
                <option value=""></option>
                <?php foreach ($data as $item): ?>
                    <option value="<?php print $item['shipping_country'] ?>" <?php if (isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <module type="custom_fields" id="shipping-info-address-<?php print $params['id'] ?>" data-for="module" default-fields="address" no-label input-class="mw-full-width mw-ui-field" data-skip-fields="country"/>
    <?php endif;?>

    <?php if($enable_custom_shipping_fields) :?>
        <module type="custom_fields" for-id="shipping"  for-id="shipping"  default-fields="message"   />
    <?php endif;?>
</div>
