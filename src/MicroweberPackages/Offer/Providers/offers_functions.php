<?php

if (!function_exists('offers_get_products')) {
    function offers_get_products()
    {

        $findCache = cache_get('offers_get_products', 'offers', 3600);
        if ($findCache) {
            return $findCache;
        }

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

        cache_save($specialOffers, 'offers_get_products', 'offers', 3600);

        return $specialOffers;
    }
}
if (!function_exists('offers_get_price')) {

    function offers_get_price($product_id, $price_id)
    {
        return app()->offer_repository->getPrice($product_id, $price_id);

    }

}
if (!function_exists('offers_get_by_product_id')) {
    function offers_get_by_product_id($product_id)
    {
        return app()->offer_repository->getByProductId($product_id);

    }
}
if (!function_exists('offers_get_offer_product_ids')) {

    function offers_get_offer_product_ids()
    {
        $offers = DB::table('offers')->select('product_id')->get();
        $product_ids = array();
        foreach ($offers as $offer) {
            $product_ids[] = $offer->product_id;
        }
        return $product_ids;
    }

}
if (!function_exists('offer_get_by_id')) {

    function offer_get_by_id($offer_id)
    {
        return app()->offer_repository->getById($offer_id);
    }
}
