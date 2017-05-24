<?php // require_once($config['path_to_module'].'shipping_api.php');  ?>
<?php

$shipping_options = mw('shop\shipping\shipping_api')->get_active();


?>
<?php if (is_array($shipping_options) and !empty($shipping_options)) : ?>
    <script type="text/javascript">
        Gateway = function (el) {
            var val = $(el).val();
            mwd.querySelector('.mw-shipping-gateway-selected-<?php print $params['id']; ?> .module').setAttribute('data-selected-gw', val);
            mw.load_module(val, '#mw-shipping-gateway-selected-<?php print $params['id']; ?>');
        }
    </script>

    <script>mw.moduleCSS("<?php print modules_url(); ?>shop/shipping/styles.css"); </script>

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
