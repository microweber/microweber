<?php





if (!function_exists('payment_options')) {
    function payment_options($option_key = false)
    {
        return app()->shop_manager->payment_options($option_key);
    }
}
