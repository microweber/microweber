<script>mw.require("shop.js", true);</script>



<?php

/*<script>mw.moduleCSS("<?php print modules_url(); ?>shop/cart/styles.css"); </script>
*/




?>


<?php
$shop_page = false;
//$shop_page = get_content('is_shop=1');
$shop_page_first = app()->content_repository->getFirstShopPage();
if($shop_page_first){
    $shop_page= array($shop_page_first);
}

$shipping_options = mw('shop\shipping\shipping_api')->get_active();
$show_shipping_info = get_option('show_shipping', $params['id']);
if ($show_shipping_info === false or $show_shipping_info == 'y') {
    $show_shipping_stuff = true;
} else {
    $show_shipping_stuff = false;
}

$print_total = cart_total();
$checkout_link_enanbled = false;
if (!isset($params['checkout-link-enabled'])) {
    $checkout_link_enanbled = get_option('data-checkout-link-enabled', $params['id']);
} else {
    $checkout_link_enanbled = $params['checkout-link-enabled'];
}
if ($checkout_link_enanbled != 'n') {
    $checkout_page = get_option('data-checkout-page', $params['id']);
    if ($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0) {
        $checkout_page_link = content_link($checkout_page) . '/view:checkout';
    } else {
        $checkout_page_link = site_url('checkout');
    }
} else {
    $checkout_page_link = false;
}

$cart_price_summary = true;

//$taxes_enabled = get_option('enable_taxes', 'shop') == 1;
//
//
//$cart_price_summary = array();
//if (function_exists('cart_get_tax') and $taxes_enabled) {
//    $cart_price_summary['tax'] = array(
//        'label' => _e("Tax", true),
//        'value' => currency_format(cart_get_tax())
//    );
//}
//
//
//if (function_exists('cart_get_discount_text') && cart_get_discount() > 0) {
//    $cart_price_summary['discount'] = array(
//        'label' => _e("Discount", true),
//        'value' => '-' . cart_get_discount_text()
//    );
//}





$cart_totals = mw()->cart_manager->totals();


$template = get_option('template', $params['id']);
$template_css_prefix = '';
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
$sid = mw()->user_manager->session_id();
if ($sid == '') {
    // //session_start();
}
$cart = array();
$cart['session_id'] = mw()->user_manager->session_id();
$cart['order_completed'] = 0;

$data = get_cart($cart);

if (is_file($template_file) and !isset($params['hide-cart'])) {
    include($template_file);
}
