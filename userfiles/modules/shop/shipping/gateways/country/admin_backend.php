<?php
must_have_access();

use MicroweberPackages\View\View;
?>
<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Shipping to country'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Unit settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#tab_shipping_fields_settings"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Fields settings'); ?></a>
</nav>

<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="list">
        <script type="text/javascript">
            mw.require('options.js');

            __shipping_options_save_msg = function () {
                if (mw.notification != undefined) {
                    mw.notification.success('Shipping options are saved!');
                }
                mw.reload_module_everywhere('shop/shipping');
                mw.reload_module_everywhere('shop/shipping/admin');

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
        $shipping_modules = get_modules("type=shipping_gateway&module=shop/shipping/gateways/country");
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

                    <div class="text-end text-right d-none">
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="mw.tools.open_global_module_settings_modal('shop/shipping/set_units', 'shipping');"><?php _e("Set shipping units"); ?></a>
                        <a class="btn btn-primary btn-sm" href="javascript:mw_admin_edit_country_item_popup();"><?php _e("Add Country"); ?></a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>





        <?php

        //use MicroweberPackages\View\View;

        include __DIR__ . "/_admin_data.php";


        $view_file = __DIR__ . DS . 'views/admin_add_shipping.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('config', $config);
        $view->assign('countries', $countries);
        $view->assign('countries_used', $countries_used);
        $view->assign('has_data', $has_data);
        print $view->display();


        $view_file = __DIR__ . DS . 'views/admin_table_list.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('config', $config);
        $view->assign('countries', $countries);
        $view->assign('countries_used', $countries_used);
        $view->assign('data', $data_active);
        $view->assign('data_key', 'data_active');
        $view->assign('active_or_disabled', 'active');

        print $view->display();

        $view_file = __DIR__ . DS . 'views/admin_table_list.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('config', $config);
        $view->assign('countries', $countries);
        $view->assign('countries_used', $countries_used);

        $view->assign('data', $data_disabled);
        $view->assign('data_key', 'data_disabled');
        $view->assign('active_or_disabled', 'disabled');
        print $view->display();


        ?>




    </div>

    <div class="tab-pane fade" id="settings">
        <module type="shop/shipping/set_units"/>
    </div>


    <div class="tab-pane fade" id="tab_shipping_fields_settings">
        <module type="shop/shipping/gateways/country/shipping_fields_settings" id="shipping_fields_settings"/>
    </div>
</div>













<?php
/*
<script>
    $(document).ready(function () {



        $('.toggle-item', '#<?php print $params['id'] ?>' ).on('click', function (e) {

            if ($(e.target).hasClass('toggle-item') || (e.target).nodeName == 'TD') {
                $(this).find('.hide-item').toggleClass('hidden');
                $(this).closest('.toggle-item').toggleClass('closed-fields');
                e.stopPropagation();
                e.preventDefault();
            }



        });
    });
</script>*/

?>
