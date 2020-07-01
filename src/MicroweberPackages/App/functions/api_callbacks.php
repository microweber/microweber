<?php

use Microweber\Utils\Adapters\Media\Unsplash;

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
	$unsplash = new Unsplash();
	
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
	
	$unsplash = new Unsplash();
	if (isset($data['photo_id'])) {
		$image = $unsplash->download($data['photo_id']);
	}
	
	return $image;
	
});


api_expose_admin('content/get_admin_js_tree_json', function ($params) {
   return mw()->category_manager->get_admin_js_tree_json($params);
});

api_expose_admin('content/get_admin_js_tree_json___', function ($params) {



//    json
//
//id: 5
//type: page
//parent_type: page
//parent_id: 3
//title: my page
//
//
//id: 1115
//type: category
//parent_type: page
//parent_id: 5
//title: category title
//
//
//id: 1116
//type: category
//parent_type: category
//parent_id: 1115
//title: sub category title


    $json = array();

    $pages_params = array();
    $pages_params['no_limit'] = 1;
    $pages_params['order_by'] = 'position desc';

    if(isset($params['is_shop'])){
         $pages_params['is_shop'] = intval($params['is_shop']);

    }


    $pages = get_pages($pages_params);
    if ($pages) {
        foreach ($pages as $page) {
            $item = array();
            $item['id'] = $page['id'];
            $item['type'] = 'page';
            $item['parent_id'] = intval($page['parent']);
            $item['parent_type'] = 'page';
            $item['title'] = $page['title'];
            // $item['has_children'] = 0;

            $item['subtype'] = $page['subtype'];
            $item['order_by'] = 'position asc';

            if ($page['is_shop']) {
                $item['subtype'] = 'shop';
            }

            if ($page['is_home']) {
                $item['subtype'] = 'home';
            }
            $item['position'] = intval($page['position']);

            $pages_cats = get_categories('parent_page=' . $page['id'].'&no_limit=1&order_by=position asc');
            if ($pages_cats) {
                //  $item['has_children'] = 1;
            } else {
                // $pages = get_pages('no_limit=1');

            }

            $json[] = $item;
            if ($pages_cats) {
                foreach ($pages_cats as $cat) {
                    $item = array();
                    $item['id'] = intval($cat['id']);
                    $item['type'] = 'category';

                    $item['parent_id'] = intval($page['id']);

                    $item['parent_type'] = 'page';
                    $item['title'] = $cat['title'];

                    $item['subtype'] = 'category';
                    $item['position'] = intval($cat['position']);

                    $json[] = $item;

                    /*

                    $cats_sub = get_category_children($cat['id']);
                    if ($cats_sub) {
                        //         $item['has_children'] = 1;
                    }
                    $json[] = $item;

                    if ($cats_sub) {
                        foreach ($cats_sub as $cat_sub_id) {
                            $cat_sub = get_category_by_id($cat_sub_id);
                            if ($cat_sub) {
                                $item = array();
                                $item['id'] = $cat_sub['id'];
                                $item['type'] = 'category';
                                $item['parent_id'] = intval($cat['id']);
                                $item['position'] = intval($cat['position']);

                                $item['parent_type'] = 'category';
                                $item['title'] = $cat_sub['title'];
                                //  $item['has_children'] = 0;
                                $item['subtype'] = 'sub_category';
                               // $item['order_by'] = 'position asc';

                                $cats_sub1 = get_category_children($cat_sub['id']);
                                if ($cats_sub1) {
                                    foreach ($cats_sub1 as $cat_sub1_id) {
                                        $cat_sub1 = get_category_by_id($cat_sub1_id);
                                        if ($cat_sub1) {
                                            $json[] = array(
                                                'id'=>$cat_sub1['id'],
                                                'type'=>'category',
                                                'title'=>$cat_sub1['title'],
                                                'parent_id'=>intval($cat_sub1['parent_id']),
                                                'position'=>intval($cat_sub1['position']),
                                                'parent_type'=> 'category',
                                                'subtype' => 'sub_category'

                                            );
                                        }
                                    }
                                    //$item['has_children'] = 1;
                                }
                                //   $item['content_subtype'] = 'sub_category';
                                $json[] = $item;
                            }
                        }
                    }
                    */
                }
            }
        }
    }

    return $json;
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


api_expose_admin('content/reset_modules_settings', function ($data) {
    return mw()->content_manager->helpers->reset_modules_settings($data);
});

api_expose_admin('content/bulk_assign', function ($data) {
    return mw()->content_manager->helpers->bulk_assign($data);
});
api_expose_admin('content/copy', function ($data) {
    return mw()->content_manager->helpers->copy($data);
});

api_expose_admin('content/redirect_to_content', function ($data) {

    if (isset($data['id'])) {
        $id = intval($data['id']);
        $url = content_link($id);
        if (!$url) {
            $url = site_url();
        }
        return redirect($url);
    }
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

api_expose('media/delete_media_file');


// queue

api_expose('queue_dispatch', function () {
    return;
    mw()->event_manager->trigger('mw.queue.dispatch');

    $all_queue = Jobs::whereNull('mw_processed')->get();

    if ($all_queue) {
        foreach ($all_queue as $queue_item) {
            $payload = $queue_item->payload;
            if($payload){
                $payload =  @json_decode($payload,true);
                $command = @unserialize($payload['data']['command']);
              //  $queue_item->mw_processed=1;
               // $queue_item->save();
           //     $queue_item->delete();


                if(is_object($command)){

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

                    $dispatcher->dispatchNow($command );





             //   $handler->call($command,$payload['data']);

                    dd($payload,$command);
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

    $job = Queue::push('\Microweber\Utils\Import', ['export' => '']);
    //dispatch($job)->onQueue('high');
    //dd($job);

    // \Illuminate\Queue\Worker;


    $job = mw('\Microweber\Utils\Import', ['export' => '']);
    dispatch($job)->onQueue('high');

    //dispatch($job);
});


