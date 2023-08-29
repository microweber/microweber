<?php
$payment_options = payment_options();
$enable_payment_options_count = 0;
?>


<?php
if (count($payment_options) == 0) {
    ?>
    <div class="alert alert-danger">
        <?php _e("There no payment methods available."); ?>
        <?php
        if (is_admin()) {
            echo '<br /><a href="'.admin_url('view:shop/action:options?group=shop/payments/admin').'" target="_blank">' . _e("Setup payment methods", true). '</a>';
        }
        ?>
    </div>
    <?php
    return;
}
?>

<script>mw.moduleCSS("<?php print modules_url(); ?>shop/payments/styles.css"); </script>

<script type="text/javascript">
    $(document).ready(function () {

        mw.$('.mw-payment-gateway-<?php print $params['id']; ?> input').commuter(function () {
            mw.trigger('mw.cart.paymentMethodChange');
            mw.$('.mw-payment-gateway-selected-<?php print $params['id']; ?> .module:first').attr('data-selected-gw', this.value);
            mw.load_module('' + this.value, '#mw-payment-gateway-selected-<?php print $params['id']; ?>');
        });

    });
</script>

<?php if (is_array($payment_options) and !empty($payment_options)) : ?>

    <?php
    $template_file_default = module_templates($config['module'], 'default');


    $module_template = get_option('template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }
    if ($module_template != false) {
        $template_file = module_templates($config['module'], $module_template);
    } else {
    }


    if (isset($template_file) and is_file($template_file) != false) {
        include($template_file);
    } else if (is_file($template_file_default) != false) {
        include($template_file_default);
    } else {
        print lnotif("No template found. Please choose template.");
    }
    ?>

<?php else : ?>
    <?php print lnotif("Click here to edit Payment Options"); ?>
<?php endif; ?>
