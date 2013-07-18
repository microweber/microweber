<?php

/**
 *
 * Shop module api
 *
 * @package        modules
 * @subpackage        shop
 * @since        Version 0.1
 */

// ------------------------------------------------------------------------

if (!defined("MODULE_DB_SHOP")) {
    define('MODULE_DB_SHOP', MW_TABLE_PREFIX . 'cart');
}

if (!defined("MODULE_DB_SHOP_ORDERS")) {
    define('MODULE_DB_SHOP_ORDERS', MW_TABLE_PREFIX . 'cart_orders');
}

if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
    define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
}


action_hook('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return \Shop::create_mw_shop_default_options();

}

action_hook('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop">' . _e('Online Shop', true) . '</a></li>';
}

action_hook('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = \mw\Notifications::get('module=shop&rel=cart_orders&is_read=n&count=1');
    if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }

    $ord_pending = get_orders('count=1&order_status=[null]&is_completed=y');
    $neword = '';
    if ($ord_pending > 0) {
        $neword = '<span class="icounter">' . $ord_pending . ' new</span>';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop/action:orders"><span class="ico iorder">' . $notif_html . '</span>' . $neword . '<span>View Orders</span></a></li>';
}

api_expose('update_order');
/**
 * update_order
 *
 * updates order by parameters
 *
 * @package        modules
 * @subpackage    shop
 * @subpackage    shop\orders
 * @category    shop module api
 */
function update_order($params = false)
{


    return \Shop::update_order($params);
}

api_expose('delete_client');

function delete_client($data)
{

    return \Shop::delete_client($data);

}

api_expose('delete_order');

function delete_order($data)
{

    return \Shop::delete_order($data);

}

function get_orders($params = false)
{

    return \Shop::get_orders($params);

}

function cart_sum($return_amount = true)
{
    return \Shop::cart_sum($return_amount);
}


api_expose('checkout_ipn');

function checkout_ipn($data)
{
    return \Shop::checkout_ipn($data);
}

api_expose('checkout');

function checkout($data)
{
    return \Shop::checkout($data);
}

function checkout_confirm_email_send($order_id, $to = false, $no_cache = false)
{

    return \Shop::checkout_confirm_email_send($order_id, $to, $no_cache);
}

api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params)
{


    return \Shop::checkout_confirm_email_test($params);
}

api_expose('update_cart');

function update_cart($data)
{
    return \Shop::update_cart($data);


}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data)
{

    return \Shop::update_cart_item_qty($data);
}

api_expose('remove_cart_item');

function remove_cart_item($data)
{

    return \Shop::remove_cart_item($data);
}

function get_cart($params)
{

    return \Shop::get_cart($params);


}

function payment_options($option_key = false)
{
    return \Shop::payment_options($option_key);


}
