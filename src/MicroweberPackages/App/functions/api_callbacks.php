<?php

api_expose('api_index', function ($data = false) {
    $fns = explode(' ', api_expose(true));
    $fns = array_filter($fns);

    if (is_admin()) {
        $fns2 = explode(' ', api_expose_admin(true));
        $fns2 = array_filter($fns2);
        $fns = array_merge($fns, $fns2);
    }

    if (isset($data['debug'])) {
        dd($fns);
    }

    return $fns;
});

// content
/*
//api_expose_admin('get_content_admin');
api_expose_admin('get_content');
api_expose_admin('get_posts');
api_expose_admin('content_title');
api_expose_admin('get_pages');
api_expose('content_link');
api_expose_admin('get_content_by_id');
api_expose_admin('get_products');
api_expose_admin('delete_content');
api_expose_admin('content_parents');
api_expose_admin('get_content_children');
api_expose_admin('page_link');
api_expose_admin('post_link');
api_expose_admin('pages_tree');
api_expose_admin('save_content');
api_expose_admin('get_content_field_draft');
api_expose_admin('get_content_field');*/
/*
api_expose_admin('notifications_manager/delete', function ($data) {
    return mw()->notifications_manager->delete($data);
});

api_expose_admin('notifications_manager/delete_selected', function ($data) {
    return mw()->notifications_manager->delete_selected($data);
});

api_expose_admin('notifications_manager/reset', function ($data) {
    return mw()->notifications_manager->reset($data);
});

api_expose_admin('notifications_manager/reset_selected', function ($data) {
    return mw()->notifications_manager->reset_selected($data);
});

api_expose_admin('notifications_manager/read', function ($data) {
    return mw()->notifications_manager->read($data);
});

api_expose_admin('notifications_manager/read_selected', function ($data) {
    return mw()->notifications_manager->read_selected($data);
});

api_expose_admin('notifications_manager/mark_all_as_read', function ($data) {
    return mw()->notifications_manager->mark_all_as_read($data);
});*/

//api_expose('template/print_custom_css', function ($data) {
//
//
//    $contents = app()->template_manager->get_custom_css($data);
//
//    $response = Response::make($contents);
//    $response->header('Content-Type', 'text/css');
//
//    return $response;
//
//});
//api_expose('template/print_custom_css_fonts', function ($data) {
//
//
//    $contents = app()->template_manager->get_custom_fonts_css_content();
//
//    $response = Response::make($contents);
//    $response->header('Content-Type', 'text/css');
//
//    return $response;
//
//});

//api_expose_admin('current_template_save_custom_css', function ($data) {
//    return mw()->layouts_manager->template_save_css($data);
//});

// SHOP
//api_expose('cart_sum');
//api_expose('checkout');
//api_expose('checkout_ipn');
//api_expose('currency_format');
//('empty_cart');
//api_expose('payment_options');



/*api_expose_admin('get_cart');
api_expose_admin('get_orders');
api_expose_admin('get_order_by_id');
api_expose_admin('checkout_confirm_email_test');
api_expose_admin('delete_client');
api_expose_admin('delete_order');
api_expose_admin('update_order');*/

/*api_expose_admin('shop/update_order', function ($data) {
    return app()->shop_manager->update_order($data);
});*/


/*api_expose_admin('shop/export_orders', function ($data) {
    return app()->order_manager->export_orders($data);
});*/


