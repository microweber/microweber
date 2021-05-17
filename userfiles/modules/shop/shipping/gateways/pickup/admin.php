<?php

must_have_access();


?>

<script type="text/javascript">
    mw.require('options.js');
    mw.require('editor.js');

    __shipping_options_save_msg = function () {
        if (mw.notification != undefined) {
            mw.notification.success('Shipping options are saved!');
        }
        mw.reload_module_everywhere('shop/shipping');
        mw.reload_module_everywhere('shop/shipping/admin');

    }

    shippingPickupSettingsSet = function (el) {
        var data = {
            option_group: 'shipping',
            option_key: 'shipping_gw_shop/shipping/gateways/pickup',
            option_value: el.checked ? 'y' : 'n'
        }
        mw.options.saveOption(data, function () {
            __shipping_options_save_msg()
        });
    }

    $(document).ready(function () {

        mw.Editor({
            selector: '.js-shipping-instructions',
            mode: 'div',
            smallEditor: false,
            minHeight: 150,
            maxHeight: '70vh',
            controls: [['bold', 'italic', 'underline', 'strikeThrough', 'link','unlink']]
        });

        mw.options.form('.mw-set-shipping-options-swticher', __shipping_options_save_msg);
    });
</script>



<?php $status = get_option('shipping_gw_shop/shipping/gateways/pickup' , 'shipping') == 'y' ? 'notification' : 'warn'; ?>

<div class="form-group">
    <label class="control-label"><?php _e("Enable pickup"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Enable or disable pickup method for shipping'); ?></small>
    <div class="custom-control custom-switch pl-0">
        <label class="d-inline-block mr-5" for="shipping_gw_shop/shipping/gateways/pickup"><?php _e('No'); ?></label>
        <input onchange="shippingPickupSettingsSet(this)" type="checkbox" name="shipping_gw_shop/shipping/gateways/pickup" id="shipping_gw_shop/shipping/gateways/pickup" data-option-group="shipping" data-id="shipping_gw_shop/shipping/gateways/pickup" data-value-checked="y" data-value-unchecked="n" class="mw_option_field custom-control-input" <?php if ($status == 'notification'): ?> checked  <?php endif; ?>>
        <label class="custom-control-label" for="shipping_gw_shop/shipping/gateways/pickup"><?php _e('Yes'); ?></label>
    </div>
</div>



<div class="form-group">
    <label class="control-label"><?php _e('Shipping instructions'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Display message to the customer when they select this shipping method.'); ?></small>
    <textarea name="shipping_pickup_instructions" placeholder="<?php _e('Ex.: Your order will be delivered to our office on address: ...'); ?>"
              class="mw_option_field form-control js-shipping-instructions" data-option-group="shipping"><?php print get_option('shipping_pickup_instructions', 'shipping') ?></textarea>
</div>

