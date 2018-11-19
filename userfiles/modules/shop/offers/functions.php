<?php

api_expose_admin('offer_save');
function offer_save($offerData = array())
{
    $json = array();
    $ok = false;
    $errorMessage = '';
    $table = 'offers';

    if (strstr($offerData['product_id'], '|')) {
        $id_parts = explode('|', $offerData['product_id']);
        $offerData['product_id'] = $id_parts[0];
        $offerData['price_key'] = $id_parts[1];
    }

    if (!is_numeric($offerData['offer_price'])) {
        $errorMessage .= 'offer price must be a number.<br />';
    }

    if (!empty($offerData['expires_at'])) {
        $date_db_format = get_date_db_format($offerData['expires_at']);
        $offerData['expires_at'] = date('Y-m-d H:i:s', strtotime($date_db_format));
    }

    if (empty($offerData['is_active'])) {
        $offerData['is_active'] = 0;
    } elseif ($offerData['is_active'] == 'on') {
        $offerData['is_active'] = 1;
    }

    if (empty($errorMessage)) {
        $ok = true;
    }

    if ($ok) {
        $offerData['price_id'] = offer_get_price_id_by_key($offerData['price_key']);
        $offerId = db_save($table, $offerData);
        $json['offer_id'] = $offerId;
        $json['success_edit'] = true;
    } else {
        $json['error_message'] = $errorMessage;
    }

    return $json;
}

api_expose_admin('offers_get_all');
function offers_get_all()
{
    $table = 'offers';

    $offers = DB::table($table)->select('offers.id', 'offers.product_id', 'offers.price_key', 'offers.offer_price', 'offers.created_at', 'offers.updated_at', 'offers.expires_at', 'offers.is_active', 'content.title as product_title', 'content.is_deleted', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
        ->leftJoin('content', 'offers.product_id', '=', 'content.id')
        ->where('content.content_type', '=', 'product')
        //->where('content.is_deleted', '=', 0)
        ->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
        ->where('custom_fields.type', '=', 'price')
        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
        ->get()
        ->toArray();

    $specialOffers = array();
    foreach ($offers as $offer) {
        $specialOffers[] = get_object_vars($offer);
    }

    return $specialOffers;
}

//api_expose('offers_get_price');
function offers_get_price($product_id, $price_key)
{
    $offer = DB::table('offers')->select('id', 'offer_price')->where('product_id', '=', $product_id)->where('price_key', '=', $price_key)->first();
    return $offer;
}

api_expose('offers_get_by_product_id');
function offers_get_by_product_id($product_id)
{
    $table = 'offers';

    $offers = DB::table($table)->select('offers.offer_price', 'offers.price_key', 'offers.expires_at', 'custom_fields.name as price_name', 'custom_fields_values.value as price')
        ->leftJoin('content', 'offers.product_id', '=', 'content.id')
        ->leftJoin('custom_fields', 'offers.price_key', '=', 'custom_fields.name_key')
        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
        ->where('content.id', '=', (int)$product_id)
        ->where('content.is_deleted', '=', 0)
        ->where('offers.is_active', '=', 1)
        ->where('custom_fields.type', '=', 'price')
        ->get()
        ->toArray();

    $specialOffers = array();
    foreach ($offers as $offer) {
        if (empty($offer->expires_at) || $offer->expires_at == '0000-00-00 00:00:00' || (strtotime($offer->expires_at) > strtotime("now"))) {
            // converting price_name to lowercase to match key from in FieldsManager function get line 556
            $specialOffers[strtolower($offer->price_name)] = get_object_vars($offer);
        }
    }

    return $specialOffers;
}

api_expose_admin('offer_get_by_id');
function offer_get_by_id($offer_id)
{
    $table = "offers";

    return db_get($table, array(
        'id' => $offer_id,
        'single' => true,
        'no_cache' => true
    ));
}

function offer_get_price_id_by_key($price_key)
{
    if (!is_admin()) return;

    $price_id = '';

    $table = "custom_fields";

    if ($customfield = DB::table($table)->select('id')
        ->where('name_key', '=', $price_key)
        ->where('type', '=', 'price')
        ->first()
    ) {
        $price_id = $customfield->id;
    }

    return $price_id;
}

api_expose_admin('offer_delete');
function offer_delete()
{
    if (!is_admin()) return;

    $table = "offers";
    $offerId = (int)$_POST['offer_id'];

    $delete = db_delete($table, $offerId);

    if ($delete) {
        return array(
            'status' => 'success'
        );
    } else {
        return array(
            'status' => 'failed'
        );
    }
}

function offers_get_offer_product_ids()
{
    $offers = DB::table('offers')->select('product_id')->get();
    $product_ids = array();
    foreach ($offers as $offer) {
        $product_ids[] = $offer->product_id;
    }
    return $product_ids;
}

api_expose_admin('offers_get_products');
function offers_get_products()
{
    $table = 'content';

    $offers = DB::table($table)->select('content.id as product_id', 'content.title as product_title', 'custom_fields.name as price_name', 'custom_fields.name_key as price_key', 'custom_fields_values.value as price')
        ->leftJoin('custom_fields', 'content.id', '=', 'custom_fields.rel_id')
        ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
        ->where('content.content_type', '=', 'product')
        ->where('content.is_deleted', '=', 0)
        ->where('custom_fields.type', '=', 'price')
        ->get()
        ->toArray();

    $existingOfferProductIds = offers_get_offer_product_ids();

    $specialOffers = array();
    foreach ($offers as $offer) {
        if (!in_array($offer->product_id, $existingOfferProductIds)) {
            $specialOffers[] = get_object_vars($offer);
        }
    }

    return $specialOffers;
}
