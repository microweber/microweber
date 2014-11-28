<?php



/**
 *
 * Shop module api
 *
 * @package        modules
 * @subpackage        shop
 * @since        Version 0.1
 */
function get_cart($params = false)
{
    return mw()->shop_manager->get_cart($params);
}


api_expose('update_cart');

function update_cart($data)
{
    return mw()->shop_manager->update_cart($data);
}

api_expose('empty_cart');
function empty_cart()
{
    return mw()->shop_manager->empty_cart();
}

function checkout_url()
{
    return mw()->shop_manager->checkout_url();
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
    return mw()->shop_manager->update_order($params);
}

api_expose('delete_client');

function delete_client($data)
{
    return mw()->shop_manager->delete_client($data);
}

api_expose('delete_order');

function delete_order($data)
{
    return mw()->shop_manager->delete_order($data);
}

function get_orders($params = false)
{
    return mw()->shop_manager->get_orders($params);
}

function get_order_by_id($params = false)
{
    return mw()->shop_manager->get_order_by_id($params);
}


function cart_sum($return_amount = true)
{
    return mw()->shop_manager->cart_sum($return_amount);
}


api_expose('checkout_ipn');

function checkout_ipn($data)
{
    return mw()->shop_manager->checkout_ipn($data);
}

api_expose('checkout');

function checkout($data)
{
    return mw()->shop_manager->checkout($data);
}


api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params)
{
    return mw()->shop_manager->checkout_confirm_email_test($params);
}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data)
{
    return mw()->shop_manager->update_cart_item_qty($data);
}

api_expose('remove_cart_item');

function remove_cart_item($data)
{
    return mw()->shop_manager->remove_cart_item($data);
}


function payment_options($option_key = false)
{
    return mw()->shop_manager->payment_options($option_key);
}


function currency_format($amount, $curr = false)
{
    return mw()->shop_manager->currency_format($amount, $curr);
}


// event_bind('recover_shopping_cart', 'mw_shop_recover_shopping_cart');

function mw_shop_recover_shopping_cart($sid = false)
{

    return mw()->shop_manager->recover_shopping_cart($sid);
}





// event_bind('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return mw()->shop_manager->create_mw_shop_default_options();

}
