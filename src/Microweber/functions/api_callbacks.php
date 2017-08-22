<?php

api_expose('api_index', function ($data = false) {
    $fns = explode(' ', api_expose(true));
    $fns = array_filter($fns);

    if (is_admin()){
        $fns2 = explode(' ', api_expose_admin(true));
        $fns2 = array_filter($fns2);
        $fns = array_merge($fns, $fns2);
    }

    if (isset($data['debug'])){
        dd($fns);
    }

    return $fns;
});

// content

api_expose_admin('get_content_admin');
api_expose_admin('get_content');
api_expose_admin('get_posts');
api_expose_admin('content_title');
api_expose_admin('get_pages');
api_expose('content_link');
api_expose_admin('get_content_by_id');
api_expose_admin('get_products');
api_expose_admin('delete_content');
api_expose_admin('content/delete', function ($data) {
    return mw()->content_manager->helpers->delete($data);
});
api_expose_admin('content_parents');
api_expose_admin('get_content_children');
api_expose_admin('page_link');
api_expose_admin('post_link');
api_expose_admin('pages_tree');
api_expose_admin('save_edit');
api_expose_admin('save_content');
api_expose('save_content_admin');
api_expose_admin('get_content_field_draft');
api_expose_admin('get_content_field');

api_expose_admin('notifications_manager/delete', function ($data) {
    return mw()->notifications_manager->delete($data);
});

api_expose_admin('notifications_manager/reset', function ($data) {
    return mw()->notifications_manager->reset($data);
});
api_expose_admin('notifications_manager/reset', function ($data) {
    return mw()->notifications_manager->reset($data);
});
api_expose_admin('notifications_manager/mark_all_as_read', function ($data) {
    return mw()->notifications_manager->mark_all_as_read($data);
});

api_expose('template/print_custom_css', function ($data) {

    $contents = mw()->template->get_custom_css($data);

    $response = Response::make($contents);
    $response->header('Content-Type', 'text/css');

    return $response;

});

api_expose_admin('content/set_published', function ($data) {
    return mw()->content_manager->set_published($data);
});

api_expose_admin('content/set_unpublished', function ($data) {
    return mw()->content_manager->set_unpublished($data);
});
api_expose_admin('content/reorder', function ($data) {
    return mw()->content_manager->reorder($data);
});

api_expose_admin('content/reset_edit', function ($data) {
    return mw()->content_manager->helpers->reset_edit_field($data);
});
api_expose_admin('content/bulk_assign', function ($data) {
    return mw()->content_manager->helpers->bulk_assign($data);
});
api_expose_admin('content/copy', function ($data) {
    return mw()->content_manager->helpers->copy($data);
});

api_expose_admin('current_template_save_custom_css', function ($data) {
    return mw()->layouts_manager->template_save_css($data);
});

// SHOP
api_expose('cart_sum');
api_expose('checkout');
api_expose('checkout_ipn');
api_expose('currency_format');
api_expose('empty_cart');
api_expose('payment_options');
api_expose('remove_cart_item');
api_expose('update_cart');
api_expose('update_cart_item_qty');

api_expose('shop/redirect_to_checkout', function () {
    return mw()->shop_manager->redirect_to_checkout();
});


api_expose_admin('get_cart');
api_expose_admin('get_orders');
api_expose_admin('get_order_by_id');
api_expose_admin('checkout_confirm_email_test');
api_expose_admin('delete_client');
api_expose_admin('delete_order');
api_expose_admin('update_order');

api_expose_admin('shop/update_order', function ($data) {
    return mw()->shop_manager->update_order($data);
});

api_expose_admin('shop/save_tax_item', function ($data) {
    return mw()->tax_manager->save($data);
});
api_expose_admin('shop/delete_tax_item', function ($data) {
    return mw()->tax_manager->delete_by_id($data);
});

api_expose_admin('shop/export_orders', function ($data) {
    return mw()->order_manager->export_orders($data);
});

// media

api_expose('delete_media_file');
api_expose('upload_progress_check');
api_expose('upload');
api_expose('reorder_media');
api_expose('delete_media');
api_expose('save_media');

api_expose('pixum_img');
api_expose('thumbnail_img');
api_expose('create_media_dir');

api_expose('media/upload');
api_expose('media/delete_media_file');


// queue

api_expose('queue_dispatch', function () {
    mw()->event_manager->trigger('mw.queue.dispatch');
});
api_expose('queue_dispatch1', function () {

 //   $job = \Queue::push('App\Jobs\CheckTopic', ['url' => $url]);

    $job = Queue::push('\Microweber\Utils\Import', ['export' => '']);
    //dispatch($job)->onQueue('high');
dd($job);

    // \Illuminate\Queue\Worker;


    $job = mw('\Microweber\Utils\Import', ['export' => '']);
    dispatch($job)->onQueue('high');

    //dispatch($job);
});


