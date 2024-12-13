<?php

if (!function_exists('checkout_url')) {
    function checkout_url()
    {
        return app()->shop_manager->checkout_url();
    }
}


if (!function_exists('checkout')) {
    function checkout($data)
    {
        return app()->shop_manager->checkout($data);
    }
}

if (!function_exists('checkout_confirm_email_test')) {
    function checkout_confirm_email_test($params)
    {
        return app()->shop_manager->checkout_confirm_email_test($params);
    }
}

if (!function_exists('checkout_ipn')) {
    function checkout_ipn($data)
    {
        return app()->shop_manager->checkout_ipn($data);
    }
}

if (!function_exists('checkout_get_user_info')) {
    function checkout_get_user_info($key=false)
    {
        return app()->checkout_manager->getUserInfo($key);
    }
}

if (!function_exists('checkout_set_user_info')) {
    function checkout_set_user_info($key=false,$value=false)
    {
        return app()->checkout_manager->setUserInfo($key,$value);
    }
}

