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

api_expose_admin('get_content_admin');
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
api_expose('save_content_admin');
api_expose_admin('get_content_field_draft');
api_expose_admin('get_content_field');

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
});

api_expose('template/print_custom_css', function ($data) {


    $contents = mw()->template->get_custom_css($data);

    $response = Response::make($contents);
    $response->header('Content-Type', 'text/css');

    return $response;

});

api_expose_admin('media_library/search', function ($data) {

    $search = array();
    $unsplash = new \MicroweberPackages\Utils\Media\Adapters\Unsplash();

    $page = 1;

    if (isset($data['page'])) {
        $page = $data['page'];
    }

    if (isset($data['keyword'])) {
        $search = $unsplash->search($data['keyword'], $page);
    }

    $response = Response::make($search);
    $response->header('Content-Type', 'text/json');

    return $response;

});

api_expose_admin('media_library/download', function ($data) {

    $unsplash = new \MicroweberPackages\Utils\Media\Adapters\Unsplash();
    if (isset($data['photo_id'])) {
        $image = $unsplash->download($data['photo_id']);
    }

    return $image;

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


api_expose_admin('shop/export_orders', function ($data) {
    return mw()->order_manager->export_orders($data);
});

// media




\Illuminate\Support\Facades\Route::get('/api/image-generate-tn-request/{cache_id}', function ($mediaId) {

    $check = \MicroweberPackages\Media\Models\MediaThumbnail::where('id', $mediaId)->first();

    if ($check) {
        $opts = $check->image_options;
        $opts = app()->url_manager->replace_site_url_back($opts);
        $cache_id_data_json = $opts;
        $cache_id_data_json['cache_id'] = $check->rel_id;

        $tn = mw()->media_manager->thumbnail_img($cache_id_data_json);
        return $tn;
    }


    return mw()->media_manager->pixum_img();
});




// queue
api_expose('queue_dispatch', function () {
    return;
    mw()->event_manager->trigger('mw.queue.dispatch');

    $all_queue = Jobs::whereNull('mw_processed')->get();

    if ($all_queue) {
        foreach ($all_queue as $queue_item) {
            $payload = $queue_item->payload;
            if ($payload) {
                $payload = @json_decode($payload, true);
                $command = @unserialize($payload['data']['command']);
                //  $queue_item->mw_processed=1;
                // $queue_item->save();
                //     $queue_item->delete();


                if (is_object($command)) {

                    $app = app();
                    //    $command = (clone $command);
                    $app->make('queue');

                    //    $app->register(get_class($command));
                    //  $app->bind('Illuminate\Contracts\Queue\Job',get_class($command));

                    //  $command::dispatch($command);
                    //  dd($command);
                    $dispatcher = $app->make('Illuminate\Contracts\Bus\Dispatcher');
                    $h = $app->make('Illuminate\Queue\CallQueuedHandler');

                    // $job = app('Illuminate\Contracts\Queue\Job');

                    //    $handler = new \Illuminate\Queue\CallQueuedHandler($dispatcher);

                    $dispatcher->dispatchNow($command);


                    //   $handler->call($command,$payload['data']);


                    //  $dis =  dispatch($command);
//dd($dis );
                }

            }
        }
    }
    // dd($all_queue);
    // php artisan queue:work


    // $queue = new Illuminate\Queue\QueueManager(app());
    //  $queue->pushRaw();

    //  dd($queue);

//
//    foreach (Stores::all() as $store) {
//        ParseFeedJob::dispatch($store);
//    }
//
//
//    Queue::before(function (JobProcessing $event) {
//        // $event->connectionName
//        // $event->job
//        // $event->job->payload()
//    });
//
//    Queue::after(function (JobProcessed $event) {
//        // $event->connectionName
//        //    print  $event->job;
//        // $event->job->payload()
//    });
//    Queue::looping(function () {
//
//
//    });

});
api_expose('queue_dispatch1', function () {

    //   $job = \Queue::push('App\Jobs\CheckTopic', ['url' => $url]);

    $job = Queue::push('\MicroweberPackages\Utils\Import', ['export' => '']);
    //dispatch($job)->onQueue('high');
    //dd($job);

    // \Illuminate\Queue\Worker;


    $job = mw('\MicroweberPackages\Utils\Import', ['export' => '']);
    dispatch($job)->onQueue('high');

    //dispatch($job);
});


