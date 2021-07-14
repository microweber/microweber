<script type="text/javascript">
    mw.require('forms.js');
    mw.require('options.js');
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#shipping-units-setup', function () {
            mw.notification.success("<?php _ejs("Shipping units are saved!"); ?>");
        });

        if (self !== parent && typeof _binded === 'undefined') {
            _binded = true;
            $(document.body).ajaxStop(function () {
                if (parent != undefined && parent.mw != undefined) {
                    mw.parent().reload_module("shop/shipping/gateways/country");
                }
            });
        }
    });
</script>

<div id="shipping-units-setup">
    <h5 class="font-weight-bold"><?php _e('Shipping units'); ?></h5>
    <small class="text-muted d-block mb-4"><?php _e("Select in which units the transport shipment will be calculated"); ?></small>

    <div class="form-group">
        <label class="control-label d-block"><?php _e("Units for weight"); ?></label>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input name="shipping_weight_units" class="mw_option_field custom-control-input" id="shipping_weight_units_1" data-option-group="orders" value="kg" type="radio" <?php if (get_option('shipping_weight_units', 'orders') == 'kg'): ?> checked="checked" <?php endif; ?> >

            <label class="custom-control-label" for="shipping_weight_units_1"><?php _e("kilograms (kg)"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input name="shipping_weight_units" class="mw_option_field custom-control-input" id="shipping_weight_units_2" data-option-group="orders" value="lb" type="radio" <?php if (get_option('shipping_weight_units', 'orders') == 'lb'): ?> checked="checked" <?php endif; ?> >

            <label class="custom-control-label" for="shipping_weight_units_2"><?php _e("pound (lb)"); ?></label>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label d-block"><?php _e("Units for size"); ?></label>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input name="shipping_size_units" class="mw_option_field custom-control-input" id="shipping_size_units_1" data-option-group="orders" value="cm" type="radio" <?php if (get_option('shipping_size_units', 'orders') == 'cm'): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="shipping_size_units_1"><?php _e("centimeters (cm)"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input name="shipping_size_units" class="mw_option_field custom-control-input" id="shipping_size_units_2" data-option-group="orders" value="in" type="radio" <?php if (get_option('shipping_size_units', 'orders') == 'in'): ?> checked="checked" <?php endif; ?> >

            <label class="custom-control-label" for="shipping_size_units_2"><?php _e("inches (in)"); ?></label>
        </div>
    </div>
</div>
