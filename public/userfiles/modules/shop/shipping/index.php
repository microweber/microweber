<?php

//$shipping_options = mw('shop\shipping\shipping_api')->get_active();
$shipping_options =app()->shipping_manager->getShippingModules();
$selected_shipping_gateway= $this->app->user_manager->session_get('shipping_provider');

if(!$selected_shipping_gateway and $shipping_options and isset($shipping_options[0]) and  isset($shipping_options[0]['module_base'])){
    $selected_shipping_gateway = $shipping_options[0]['module_base'];
}
?>

<?php
if (count($shipping_options) == 0) {
    ?>
    <div class="alert alert-primary">
        <?php _e("There no shipping methods available."); ?>
        <?php
        if (is_admin()) {
          echo '<br /><a href="'.admin_url('settings?group=shop/shipping/admin').'" target="_blank">' . _e("Setup shipping methods", true). '</a>';
        }
        ?>
    </div>
<?php
return;
}
?>

<?php if (is_array($shipping_options) and !empty($shipping_options)) : ?>
    <script type="text/javascript">
        Gateway = function (el) {
            var val = $(el).val();
            $.ajax({
                url: "<?php print route('shop.shipping.set_provider') ?>",
                data: {"provider":val},
                method: 'POST',
            }).done(function() {
                mw.reload_module('shop/cart');
                $('#mw-shipping-gateway-selected-<?php print $params['id']; ?>').attr('data-type',val);

                mw.reload_module('#mw-shipping-gateway-selected-<?php print $params['id']; ?>');
                //document.querySelector('.mw-shipping-gateway-selected-<?php print $params['id']; ?> .module').setAttribute('data-selected-gw', val);
            //   mw.load_module(val, '#mw-shipping-gateway-selected-<?php print $params['id']; ?>');

            });
        }
    </script>


    <?php
    $module_template = get_option('data-template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }
    if ($module_template != false) {
        $template_file = module_templates($config['module'], $module_template);
    } else {
        $template_file = module_templates($config['module'], 'default');
    }

    if (is_file($template_file) != false) {
        include($template_file);
    } else {
        print lnotif("No template found. Please choose template.");
    }
    ?>

<?php else : ?>
    <?php print lnotif(_e("Click here to edit Shipping Options", true)); ?>
<?php endif; ?>
