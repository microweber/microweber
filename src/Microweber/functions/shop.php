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


function update_cart($data)
{
    return mw()->shop_manager->update_cart($data);
}


function empty_cart()
{
    return mw()->shop_manager->empty_cart();
}

function checkout_url()
{
    return mw()->shop_manager->checkout_url();
}


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


function delete_client($data)
{
    return mw()->shop_manager->delete_client($data);
}

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


function checkout_ipn($data)
{
    return mw()->shop_manager->checkout_ipn($data);
}


function checkout($data)
{
    return mw()->shop_manager->checkout($data);
}


function checkout_confirm_email_test($params)
{
    return mw()->shop_manager->checkout_confirm_email_test($params);
}

function update_cart_item_qty($data)
{
    return mw()->shop_manager->update_cart_item_qty($data);
}


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


function mw_shop_recover_shopping_cart($sid = false)
{
    return mw()->shop_manager->recover_shopping_cart($sid);
}


function create_mw_shop_default_options()
{
    return mw()->shop_manager->create_mw_shop_default_options();
}
