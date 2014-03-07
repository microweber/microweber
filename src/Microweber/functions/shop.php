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


function get_cart($params = false)
{
    return mw('shop')->get_cart($params);
}


api_expose('update_cart');

function update_cart($data)
{
    return mw('shop')->update_cart($data);
}

function empty_cart()
{
    return mw('shop')->empty_cart();
}

function checkout_url()
{
    return site_url('checkout');
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
    return mw('shop')->update_order($params);
}

api_expose('delete_client');

function delete_client($data)
{
    return mw('shop')->delete_client($data);
}

api_expose('delete_order');

function delete_order($data)
{
    return mw('shop')->delete_order($data);
}

function get_orders($params = false)
{
    return mw('shop')->get_orders($params);
}

function get_order_by_id($params = false)
{
    return mw('shop')->get_order_by_id($params);
}


function cart_sum($return_amount = true)
{
    return mw('shop')->cart_sum($return_amount);
}


api_expose('checkout_ipn');

function checkout_ipn($data)
{
    return mw('shop')->checkout_ipn($data);
}

api_expose('checkout');

function checkout($data)
{
    return mw('shop')->checkout($data);
}


api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params)
{
    return mw('shop')->checkout_confirm_email_test($params);
}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data)
{
    return mw('shop')->update_cart_item_qty($data);
}

api_expose('remove_cart_item');

function remove_cart_item($data)
{
    return mw('shop')->remove_cart_item($data);
}


function payment_options($option_key = false)
{
    return mw('shop')->payment_options($option_key);
}


function currency_format($amount, $curr = false)
{
    return mw('shop')->currency_format($amount, $curr);
}


event_bind('recover_shopping_cart', 'mw_shop_recover_shopping_cart');

function mw_shop_recover_shopping_cart($sid = false)
{

    return mw('shop')->recover_shopping_cart($sid);
}


event_bind('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = mw('Notifications')->get('module=shop&rel=cart_orders&is_read=n&count=1');
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


event_bind('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return mw('shop')->create_mw_shop_default_options();

}
