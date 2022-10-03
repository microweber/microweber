<?php


//autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Shop\\Offers\\');



//api_expose_admin('offer_save');
//function offer_save($offerData = array())
//{
//    $json = array();
//    $ok = false;
//    $errorMessage = '';
//    $table = 'offers';
//
//    if (isset($offerData['product_id_with_price_id'])) {
//        $id_parts = explode('|', $offerData['product_id_with_price_id']);
//        $offerData['product_id'] = $id_parts[0];
//        $offerData['price_id'] = $id_parts[1];
//    } else if (isset($offerData['product_id'])) {
//        if (strstr($offerData['product_id'], '|')) {
//            $id_parts = explode('|', $offerData['product_id']);
//            $offerData['product_id'] = $id_parts[0];
//            // $offerData['price_key'] = $id_parts[1];
//        }
//    }
//    if (isset($offerData['offer_price'])) {
//        $offerData['offer_price'] = mw()->format->amount_to_float($offerData['offer_price']);
//    }
//
//    if (!is_numeric($offerData['offer_price'])) {
//        $errorMessage .= 'offer price must be a number.<br />';
//    }
//
//    if (isset($offerData['expires_at']) and trim($offerData['expires_at']) != '') {
//        //$date_db_format = get_date_db_format($offerData['expires_at']);
//        //$offerData['expires_at'] = date('Y-m-d H:i:s', strtotime($date_db_format));
//
//        // >>> Format date
//        try {
//            $carbonExpiresAt = Carbon::parse($offerData['expires_at']);
//            $offerData['expires_at'] = $carbonExpiresAt->format('Y-m-d H:i:s');
//        } catch (\Exception $e) {
//            //$data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
//        }
//        // <<< Format date
//    } else {
//        $offerData['expires_at'] = null;
//    }
//
//    if (empty($offerData['is_active'])) {
//        $offerData['is_active'] = 0;
//    } elseif ($offerData['is_active'] == 'on') {
//        $offerData['is_active'] = 1;
//    }
//
//    if (empty($errorMessage)) {
//        $ok = true;
//    }
//
//    if ($ok) {
//        $offerId = db_save($table, $offerData);
//        $json['offer_id'] = $offerId;
//        $json['success_edit'] = true;
//    } else {
//        $json['error_message'] = $errorMessage;
//    }
//
//    cache_delete('offers');
//
//    return $json;
//}

//api_expose_admin('offers_get_all');
//function offers_get_all()
//{
//    $findCache = cache_get('offers_get_all', 'offers', false);
//    if ($findCache) {
//        return $findCache;
//    }
//
//    $table = 'offers';
//
//    $offers = DB::table($table)->select(
//        'offers.id',
//        'offers.product_id',
//        //  'offers.price_key',
//        'offers.offer_price',
//        'offers.created_at',
//        'offers.updated_at',
//        'offers.expires_at',
//        'offers.is_active',
//        // 'offers.price_key as price_key',
//        'content.title as product_title',
//        'content.is_deleted',
//        'custom_fields.name as price_name',
//        'custom_fields_values.value as price'
//
//    )
//        ->where('content.content_type', '=', 'product')
//        //->where('content.is_deleted', '=', 0)
//        //  ->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
//        ->where('custom_fields.type', '=', 'price')
//        ->leftJoin('custom_fields', 'offers.price_id', '=', 'custom_fields.id')
//        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
//        ->leftJoin('content', 'offers.product_id', '=', 'content.id')
//        ->get()
//        ->toArray();
//
//    $specialOffers = array();
//    foreach ($offers as $offer) {
//        $specialOffers[] = get_object_vars($offer);
//    }
//
//    cache_save($specialOffers, 'offers_get_all', 'offers');
//
//    return $specialOffers;
//}

//Deprecated and not used anymore
//its use in src/MicroweberPackages/Utils/Misc/ContentExport.php at line 218 is also depricated as it is old export
//api_expose_admin('offers_get_products');
//function offers_get_products()
//{
//
//    $findCache = cache_get('offers_get_products', 'offers', false);
//    if ($findCache) {
//        return $findCache;
//    }
//
//    $table = 'content';
//
//    $offers = DB::table($table)->select('content.id as product_id', 'content.title as product_title', 'custom_fields.name as price_name', 'custom_fields.name_key as price_key', 'custom_fields_values.value as price')
//        ->leftJoin('custom_fields', 'content.id', '=', 'custom_fields.rel_id')
//        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
//        ->where('content.content_type', '=', 'product')
//        ->where('content.is_deleted', '=', 0)
//        ->where('custom_fields.type', '=', 'price')
//        ->get()
//        ->toArray();
//
//    $existingOfferProductIds = offers_get_offer_product_ids();
//
//    $specialOffers = array();
//    foreach ($offers as $offer) {
//        if (!in_array($offer->product_id, $existingOfferProductIds)) {
//            $specialOffers[] = get_object_vars($offer);
//        }
//    }
//
//    cache_save($specialOffers, 'offers_get_products', 'offers');
//
//    return $specialOffers;
//}

//api_expose('offers_get_price');
//function offers_get_price($product_id = false, $price_id)
//{
//    $cache_id = 'offers_get_price_' . $product_id . $price_id;
//
//    $findCache = cache_get($cache_id, 'offers', false);
//    if ($findCache) {
//        return $findCache;
//    }
//
//    $offer = DB::table('offers')->select('*');
//    if ($product_id) {
//        $offer = $offer->where('product_id', '=', $product_id);
//    }
//    $offer = $offer->where('price_id', '=', $price_id);
//
//    $offer = $offer->first();
//
//    if ($offer) {
//        if (!($offer->expires_at) || $offer->expires_at == '0000-00-00 00:00:00' || (strtotime($offer->expires_at) > strtotime("now"))) {
//
//            cache_save($offer, $cache_id, 'offers');
//
//            return (array)$offer;
//
//        }
//    }
//}

//api_expose('offers_get_by_product_id');
//function offers_get_by_product_id($product_id)
//{
//    $table = 'offers';
//    $cache_key = __FUNCTION__.$product_id;
//    $ttl = now()->addHour(1);
//
//    $query = DB::table($table)->select('custom_fields.id as id','offers.id as offer_id', 'offers.offer_price', 'offers.expires_at', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
//        ->leftJoin('content', 'offers.product_id', '=', 'content.id')
//        //   ->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
//        ->leftJoin('custom_fields', 'offers.price_id', '=', 'custom_fields.id')
//        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
//        ->where('content.id', '=', (int)$product_id)
//        ->where('content.is_deleted', '=', 0)
//        ->where('offers.is_active', '=', 1)
//        ->where('custom_fields.type', '=', 'price');
//
//
//
//
//    $offers = Cache::tags($table)->remember($cache_key, $ttl, function () use ($query) {
//        return $query->get()->toArray();
//    });
//
//
//
//
////    $offers = DB::table($table)->select('custom_fields.id as id', 'offers.offer_price', 'offers.expires_at', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
////        ->leftJoin('content', 'offers.product_id', '=', 'content.id')
////        //   ->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
////        ->leftJoin('custom_fields', 'offers.price_id', '=', 'custom_fields.id')
////        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
////        ->where('content.id', '=', (int)$product_id)
////        ->where('content.is_deleted', '=', 0)
////        ->where('offers.is_active', '=', 1)
////        ->where('custom_fields.type', '=', 'price')
////        ->get()
////        ->toArray();
//
//
//    $specialOffers = array();
//    foreach ($offers as $offer) {
//
//        if (!($offer->expires_at) || $offer->expires_at == '0000-00-00 00:00:00' || (strtotime($offer->expires_at) > strtotime("now"))) {
//            // converting price_name to lowercase to match key from in FieldsManager function get line 556
//
//            $offer_data = get_object_vars($offer);
//            if (isset($offer_data['offer_price']) and $offer_data['offer_price'] and isset($offer_data['price'])) {
//
//                $price_change_direction = 'decrease';
//                $offer_data['offer_price'] = floatval($offer_data['offer_price']);
//                $offer_data['price'] = floatval($offer_data['price']);
//
//                $answer = abs($offer_data['price'] - $offer_data['offer_price']);
//                $offer_data['price_change_direction_sign'] = '-';
//                $offer_data['offer_value_difference'] = $answer;
//
//                if ($offer_data['offer_price'] > $offer_data['price']) {
//                    $price_change_direction = 'increase';
//                    $answer = abs($offer_data['price'] - $offer_data['offer_price']);
//                    $offer_data['price_change_direction_sign'] = '+';
//                    $offer_data['offer_value_difference'] = $answer;
//                }
//
//                $percent = mw()->format->percent($offer_data['offer_value_difference'], $offer_data['price']);
//                $offer_data['offer_value_difference_percent'] = $percent;
//                $offer_data['price_change_direction'] = $price_change_direction;
//            }
//
//            $specialOffers[strtolower($offer->price_name)] = $offer_data;
//
//        }
//    }
//
//    return $specialOffers;
//}

//Deprecated; used only in offers_get_products which is also deprecated
//function offers_get_offer_product_ids()
//{
//    $offers = DB::table('offers')->select('product_id')->get();
//    $product_ids = array();
//    foreach ($offers as $offer) {
//        $product_ids[] = $offer->product_id;
//    }
//    return $product_ids;
//}


//api_expose_admin('offer_get_by_id');
//function offer_get_by_id($offer_id)
//{
//    $table = "offers";
//
//    $offer = db_get($table, array(
//        'id' => $offer_id,
//        'single' => true,
//        //  'no_cache' => true
//    ));
//    $additional_fields = array();
//    if (isset($offer['id']) and isset($offer['product_id']) and $offer['product_id']) {
//        //WAS $prod_offers = offers_get_by_product_id($offer['product_id']);
//        $prod_offers = app()->offer_repository->getByProductId($offer['product_id']);
//        if ($prod_offers) {
//            foreach ($prod_offers as $prod_offer) {
//                if ($prod_offer['id'] == $offer['id']) {
//                    $additional_fields = $prod_offer;
//                }
//            }
//        }
//    }
//
//    if ($additional_fields) {
//        $offer = array_merge($offer, $additional_fields);
//    }
//    return $offer;
//
//}


//api_expose_admin('offer_delete');
//function offer_delete($params)
//{
//    if (!isset($params['offer_id'])) {
//        return;
//    }
//
//    $table = "offers";
//    $offerId = (int)$params['offer_id'];
//
//    $delete = db_delete($table, $offerId);
//
//    if ($delete) {
//        return array(
//            'status' => 'success'
//        );
//    } else {
//        return array(
//            'status' => 'failed'
//        );
//    }
//}


event_bind('mw.shop.get_product_prices', function ($custom_field_items) {

    if ($custom_field_items) {
        foreach ($custom_field_items as $key => $price) {
            //WAS $price_on_offer = offers_get_price($price['rel_id'], $price['id']);
            $price_on_offer = app()->offer_repository->getPrice($price['rel_id'], $price['id']);
            if ($price_on_offer) {
                $price_on_offer = (array)$price_on_offer;

                $offerExpired = false;

                if(!empty($price_on_offer['expires_at']) && $price_on_offer['expires_at'] != '0000-00-00 00:00:00') {
                    $offerExpired = \Carbon\Carbon::now()->gt($price_on_offer['expires_at']);
                }

                if ($price_on_offer and isset($price_on_offer['offer_price']) and $offerExpired == false) {
                    $cust_price = $price;
                    $new_price_value = $price_on_offer['offer_price'];


                    $cust_price['custom_value'] = $new_price_value;
                    $cust_price['value'] = $new_price_value;
                    $cust_price['value_plain'] = $new_price_value;
                    $cust_price['original_value'] = $price['value'];
                    $cust_price['custom_value_module'] = 'shop/offers';
                    $cust_price['custom_value_data'] = $price_on_offer;
                    $custom_field_items[$key] = $cust_price;
                }
            }
        }
        return $custom_field_items;
    }

});

event_bind('mw.admin.custom_fields.price_settings', function ($data) {
	if (isset($data['id']) and isset($data['rel_id']) and isset($data['rel_type']) and $data['rel_type'] == 'content') {
		echo '<module type="shop/offers/price_settings" price-id="' . $data['id'] . '"  product-id="' . $data['rel_id'] . '" />';
    }
});


event_bind('mw.admin.shop.settings.offers', function ($data) {
    print '<module type="shop/offers" view="admin_block" />';
});


event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="col-4">
                <a href="#option_group=shop/offers/admin_block" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-label-percent-outline mdi-20px"></i></div>

                    <div class="info-holder">
                        <span class="text-outline-primary font-weight-bold">' . _e('Promotions', true) . '</span><br/>
                        <small class="text-muted">'. _e('Creating and managing promo campaigns', true) .'</small>
                    </div>
                </a>
            </div>';
});
