<script type="text/javascript">
    mw.require('forms.js');
    mw.require('options.js');

</script>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('#shipping-units-setup', function () {
            mw.notification.success("<?php _e("Shipping units are saved!"); ?>");
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        if (self !== parent && typeof _binded === 'undefined') {
            _binded = true;
            $(mwd.body).ajaxStop(function () {
                if (parent != undefined && parent.mw != undefined) {
                    parent.mw.reload_module("shop/shipping/gateways/country");
                }
            });
        }
    });
</script>


<div id="shipping-units-setup-accordion" class="mw-ui-box mw-ui-box-silver-blue == m-t-20">
    <div class="mw-ui-box-header" onclick="mw.accordion('#shipping-units-setup-accordion');">
        <div class="header-holder">
            <i class="mai-shipping"></i> <?php print _e('Shipping units'); ?>
        </div>
    </div>

    <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Units for weight"); ?></label>

                    <div class="mw-ui-check-selector">
                        <label class="mw-ui-check">
                            <input name="shipping_weight_units" class="mw_option_field" data-option-group="orders" value="kg" type="radio" <?php if (get_option('shipping_weight_units', 'orders') == 'kg'): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("kilograms (kg)"); ?></span>
                        </label>
                        <label class="mw-ui-check">
                            <input name="shipping_weight_units" class="mw_option_field" data-option-group="orders" value="lb" type="radio" <?php if (get_option('shipping_weight_units', 'orders') == 'lb'): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("pound (lb)"); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mw-ui-col">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Units for size"); ?></label>

                    <div class="mw-ui-check-selector">
                        <label class="mw-ui-check">
                            <input name="shipping_size_units" class="mw_option_field" data-option-group="orders" value="cm" type="radio" <?php if (get_option('shipping_size_units', 'orders') == 'cm'): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("centimeters (cm)"); ?></span>
                        </label>
                        <label class="mw-ui-check">
                            <input name="shipping_size_units" class="mw_option_field" data-option-group="orders" value="in" type="radio" <?php if (get_option('shipping_size_units', 'orders') == 'in'): ?> checked="checked" <?php endif; ?> >
                            <span></span><span><?php _e("inches (in)"); ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


