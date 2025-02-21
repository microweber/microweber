<?php


if (!function_exists('delete_client')) {
    function delete_client($data)
    {
        return app()->shop_manager->delete_client($data);
    }
}

if (!function_exists('is_product_in_stock')) {
    function is_product_in_stock($content_id)
    {
        return mw()->cart_manager->is_product_in_stock($content_id);
    }
}

if (!function_exists('get_product_prices')) {
    function get_product_prices($content_id = false, $return_full_custom_fields_array = false)
    {
        return app()->shop_manager->get_product_prices($content_id, $return_full_custom_fields_array);
    }
}

if (!function_exists('get_product_price')) {
    function get_product_price($content_id = false)
    {
        return app()->shop_manager->get_product_price($content_id);
    }
}

if (!function_exists('get_product_discount_price')) {
    function get_product_discount_price($content_id = false)
    {
        $product = \Modules\Product\Models\Product::query()->where('id', $content_id)->first();
        if ($product) {
            return $product->getSpecialPriceAttribute();
        } else {
            return false;
        }
    }
}

if (!function_exists('get_product_discount_percent')) {
    function get_product_discount_percent($content_id = false)
    {
        $product = \Modules\Product\Models\Product::query()->where('id', $content_id)->first();
        if ($product) {
            return $product->getDiscountPercentage();
        } else {
            return false;
        }
    }
}

if (!function_exists('update_order')) {
    function update_order($params = false)
    {
        return app()->shop_manager->update_order($params);
    }
}

if (!function_exists('delete_order')) {
    function delete_order($data)
    {
        return app()->order_manager->delete_order($data);
    }
}

if (!function_exists('get_orders')) {
    function get_orders($params = false)
    {
        return app()->order_manager->get($params);
    }
}

if (!function_exists('get_order_by_id')) {
    function get_order_by_id($params = false)
    {
        return app()->order_manager->get_by_id($params);
    }
}

if (!function_exists('currency_format')) {
    function currency_format($amount, $curr = false)
    {
        return app()->shop_manager->currency_format($amount, $curr);
    }
}
if (!function_exists('price_format')) {
    function price_format($amount)
    {
        return app()->shop_manager->currency_format($amount);
    }
}
if (!function_exists('format_currency')) {
    function format_currency($amount, $curr = false)
    {
        return currency_format($amount, $curr);
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol($curr = false)
    {
        return get_currency_symbol($curr);
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($curr = false)
    {
        return app()->shop_manager->currency_symbol($curr);
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        $curr = mw()->option_manager->get('currency', 'payments');
        if (!$curr) {
            $curr = 'USD';
        }
        return $curr;
    }
}

if (!function_exists('currency_code')) {
    function currency_code()
    {
        return get_currency_code();
    }
}

if (!function_exists('is_shop_module_enabled_for_user')) {
    function is_shop_module_enabled_for_user()
    {
        $shop_disabled = get_option('shop_disabled', 'website') == 'y';
        if ($shop_disabled) {
            return false;
        }

        if (!mw()->module_manager->is_installed('shop')) {
            return false;
        }

        if (!user_can_view_module(['module' => 'shop'])) {
            return false;
        }

        return true;
    }
}
