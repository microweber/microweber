<script type="text/javascript">
    mw.require('options.js');

    __shipping_options_save_msg = function () {
        if (mw.notification != undefined) {
            mw.notification.success('Shipping options are saved!');
        }
        mw.reload_module_parent('shop/shipping');

    }

    shippingToCountryClass = function () {
        var val = mw.$('[data-option-group="shipping"]:checked').val();
        if (val == 'y') {
            // mw.$("#set-shipping-to-country").removeClass('mw-ui-box-warn').addClass('mw-ui-box-notification');
        }
        else {
            // mw.$("#set-shipping-to-country").removeClass('mw-ui-box-notification').addClass('mw-ui-box-warn');
        }
    }

    $(document).ready(function () {
        mw.options.form('.mw-set-shipping-options-swticher', __shipping_options_save_msg);
    });
</script>


<?php
$here = dirname(__FILE__) . DS . 'gateways' . DS;
// $shipping_modules = scan_for_modules("cache_group=modules/global/shipping&dir_name={$here}");
$shipping_modules = get_modules("type=shipping_gateway");
?>

<div class="section-header">
    <h2 class="pull-left"><span class="mai-shipping"></span> <?php _e("Shipping"); ?></h2>
    <?php if (is_array($shipping_modules)): ?>
        <?php foreach ($shipping_modules as $shipping_module): ?>
            <?php if (mw()->modules->is_installed($shipping_module['module'])): ?>
                <div class="pull-left" id="set-shipping-to-country">
                        <?php $status = get_option('shipping_gw_' . $shipping_module['module'], 'shipping') == 'y' ? 'notification' : 'warn'; ?>

                    <span class="switcher-label-left"><?php _e("Enable shipping to countries"); ?></span>

                    <label class="mw-switch inline-switch pull-right">
                        <input
                                onchange="shippingToCountryClass()"
                                type="checkbox"
                                name="shipping_gw_<?php print $shipping_module['module'] ?>"
                                data-option-group="shipping"
                                data-id="shipping_gw_<?php print $shipping_module['module'] ?>"
                                data-value-checked="y"
                                data-value-unchecked="n"
                                class="mw_option_field"
                            <?php if ($status == 'notification'): ?> checked="y" <?php endif; ?>>
                        <span class="mw-switch-off">OFF</span>
                        <span class="mw-switch-on">ON</span>
                        <span class="mw-switcher"></span>
                    </label>
                </div>

                <div class="pull-right">
                    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline pull-right" onclick="mw.tools.open_global_module_settings_modal('shop/shipping/set_units', 'shipping');">
                        <span class="mw-icon-gear"></span><?php _e("Set shipping units"); ?>
                    </a>

                    <a class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info pull-right m-r-10" href="javascript:;" onclick="mw.$('.add-new-country').show()">
                        <span class="mw-icon-plus"></span> <?php _e("Add Country"); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<div class="mw-set-shipping-options mw-admin-wrap admin-side-content">
    <div class="">
        <?php if (is_array($shipping_modules)): ?>
            <?php foreach ($shipping_modules as $shipping_module): ?>
                <?php if (mw()->modules->is_installed($shipping_module['module'])): ?>


                    <div class="mw-set-shipping-gw-options">
                        <module type="<?php print $shipping_module['module'] ?>" view="admin"/>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
