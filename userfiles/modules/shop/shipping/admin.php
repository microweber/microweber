<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Shipping to country'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#settings"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Unit settings'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <script type="text/javascript">
                    mw.require('options.js');

                    __shipping_options_save_msg = function () {
                        if (mw.notification != undefined) {
                            mw.notification.success('Shipping options are saved!');
                        }
                        mw.reload_module_parent('shop/shipping');

                    }

                    shippingToCountryClass = function (el) {
                        var data = {
                            option_group: 'shipping',
                            option_key: 'shipping_gw_shop/shipping/gateways/country',
                            option_value: el.checked ? 'y' : 'n'
                        }
                        mw.options.saveOption(data, function () {
                            __shipping_options_save_msg()
                        });
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

                <?php if (is_array($shipping_modules)): ?>
                    <?php foreach ($shipping_modules as $shipping_module): ?>
                        <?php if (mw()->module_manager->is_installed($shipping_module['module'])): ?>
                            <?php $status = get_option('shipping_gw_' . $shipping_module['module'], 'shipping') == 'y' ? 'notification' : 'warn'; ?>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Enable shipping to countries"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e('Enable or disable shipping to countries in general'); ?></small>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="shipping_gw_<?php print $shipping_module['module'] ?>">No</label>
                                    <input onchange="shippingToCountryClass(this)" type="checkbox" name="shipping_gw_<?php print $shipping_module['module'] ?>" id="shipping_gw_<?php print $shipping_module['module'] ?>" data-option-group="shipping" data-id="shipping_gw_<?php print $shipping_module['module'] ?>" data-value-checked="y" data-value-unchecked="n" class="mw_option_field custom-control-input" <?php if ($status == 'notification'): ?> checked  <?php endif; ?>>
                                    <label class="custom-control-label" for="shipping_gw_<?php print $shipping_module['module'] ?>">Yes</label>
                                </div>
                            </div>

                            <div class="text-right d-none">
                                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="mw.tools.open_global_module_settings_modal('shop/shipping/set_units', 'shipping');"><?php _e("Set shipping units"); ?></a>
                                <a class="btn btn-primary btn-sm" href="javascript:mw_admin_edit_country_item_popup();"><?php _e("Add Country"); ?></a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (is_array($shipping_modules)): ?>
                    <?php foreach ($shipping_modules as $shipping_module): ?>
                        <?php if (mw()->module_manager->is_installed($shipping_module['module'])): ?>
                            <div class="mw-set-shipping-gw-options">
                                <module type="<?php print $shipping_module['module'] ?>" view="admin"/>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="settings">
                <module type="shop/shipping/set_units"/>
            </div>
        </div>
    </div>
</div>