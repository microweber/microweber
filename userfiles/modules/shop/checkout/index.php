<?php

$payment_success = false;
$payment_failure = false;


if (isset($_SESSION['mw_payment_success'])) {
    mw()->user_manager->session_del('mw_payment_success');
    $payment_success = true;
}

if (isset($_REQUEST['mw_payment_success'])) {
    $payment_success = true;
} elseif (isset($_REQUEST['mw_payment_failure'])) {
    $payment_success = false;
}

$step = 1;

if (isset($_GET['step'])) {
    $step = intval($_GET['step']);
}

$user = get_user();

$requires_terms = $terms = $tems = get_option('shop_require_terms', 'website') == 1;
$shop_page = get_content('is_shop=1');

$requires_registration = get_option('shop_require_registration', 'website') == '1';


$cart = array();
$cart['session_id'] = mw()->user_manager->session_id();
$cart['order_completed'] = 0;

$data = get_cart($cart);


$checkout_price_formation = array();



$cart_totals = mw()->cart_manager->totals();











$template = get_option('template', $params['id']);
$template_file = false;
$module_template = false;
if ($template != false and strtolower($template) != 'none') {
    $template_css_prefix = no_ext($template);
    $template_file = module_templates($params['type'], $template);

} else {
    if ($template == false and isset($params['template'])) {
        $module_template = $params['template'];
        $template_file = module_templates($params['type'], $module_template);
    } else {
        $template_file = module_templates($params['type'], 'default');
    }
}
?>

    <script type="text/javascript">
        mw.require("shop.js");
    </script>





    <script type="text/javascript">
        $(document).ready(function () {
            __max = 0;
            mw.$(".mw-checkout-form .well", '#<?php  print $params['id'] ?>').each(function () {
                var h = $(this).height();
                if (h > __max) {
                    __max = h;
                }
            });
            mw.$(".mw-checkout-form .well",'#<?php  print $params['id'] ?>').css("minHeight", __max);

        });
    </script>

<?php $cart_show_payments = get_option('data-show-payments', $params['id']); ?>
<?php $cart_show_shipping = get_option('data-show-shipping', $params['id']); ?>
<?php
if (is_file($template_file)) {
    include($template_file);
}
?>
