<?php

if (!function_exists('get_cart')) {
    function get_cart($params = false)
    {
        return app()->shop_manager->get_cart($params);
    }
}

if (!function_exists('update_cart')) {
    function update_cart($data)
    {
        return app()->shop_manager->update_cart($data);
    }
}

if (!function_exists('empty_cart')) {
    function empty_cart()
    {
        return app()->shop_manager->empty_cart();
    }
}

if (!function_exists('cart_sum')) {
    function cart_sum($return_amount = true)
    {
        return app()->shop_manager->cart_sum($return_amount);
    }
}

if (!function_exists('cart_get_items_count')) {
    function cart_get_items_count()
    {
        return app()->shop_manager->cart_sum(false);
    }
}

if (!function_exists('cart_total')) {
    function cart_total()
    {
        return app()->shop_manager->cart_total();
    }
}

if (!function_exists('cart_totals')) {
    function cart_totals($return = 'all')
    {
        return mw()->cart_manager->totals($return);
    }
}

if (!function_exists('cart_get_tax')) {
    function cart_get_tax()
    {
        return mw()->cart_manager->get_tax();
    }
}

if (!function_exists('cart_get_discount')) {
    function cart_get_discount()
    {
        return mw()->cart_manager->get_discount();
    }
}

if (!function_exists('cart_get_discount_text')) {
    function cart_get_discount_text()
    {
        return mw()->cart_manager->get_discount_text();
    }
}

if (!function_exists('update_cart_item_qty')) {
    function update_cart_item_qty($data)
    {
        return app()->shop_manager->update_cart_item_qty($data);
    }
}

if (!function_exists('remove_cart_item')) {
    function remove_cart_item($data)
    {
        return app()->shop_manager->remove_cart_item($data);
    }
}

if (!function_exists('mw_shop_recover_shopping_cart')) {
    function mw_shop_recover_shopping_cart($sid = false)
    {
        return app()->cart_manager->recover_cart($sid);
    }
}
