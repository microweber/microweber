<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

class ItemMapReader
{
    public static $map = [
        'content_data.mpn'=>['mpn','g:mpn'],
        'content_data.sku'=>['sku','g:sku'],
        'content_data.gtin'=>['gtin','g:gtin'],
        'content_data.external_id'=>['id','g:id'],
        'title'=>['title','g:title','name'],
        'content_body'=>['description','g:description','content','html','summary'],
        'images'=>['image','g:image_link'],
        'price'=>['price','g:price'],
        'content_data.shipping_price'=>['shipping_price','g:shipping.g:price'],
        'categories'=>['genre', 'category','g:google_product_category','g:product_type'],
        'updated_at'=>['updated_date','published'],
        'created_at'=>['publish_date','pubDate','updated'],
    ];

    public static $itemTypes = [
      'content_data.mpn'=>'MPN',
      'content_data.gtin'=>'GTIN',
      'content_data.sku'=>'SKU',
      'content_data.external_id'=>'External ID',
      'title'=>'Title',
    //  'description'=>'Description',
      'content_body'=>'Content Body',
      'images'=>'Images',
      'categories'=>'Categories',
      'price'=>'Price',
      'content_data.shipping_price'=>'Shipping Price',
      'updated_at'=>'Updated at',
      'created_at'=>'Created at',
    ];

    public const ITEM_TYPE_STRING = 'string';
    public const ITEM_TYPE_ARRAY = 'array';

    public static function getMapping($item) {

        $map = [];

        if (!empty($item)) {
            foreach ($item as $itemKey=>$itemValue) {
                foreach (self::$map as $internalKey=>$mapKeys) {
                    foreach ($mapKeys as $mapKey) {
                        if ($itemKey == $mapKey) {

                            $itemType = self::ITEM_TYPE_STRING;
                            if (is_array($itemValue)) {
                                $itemType = self::ITEM_TYPE_ARRAY;
                            }

                            $map[$itemKey] = [
                                'item_key'=>$itemKey,
                                'item_type'=>$itemType,
                                'internal_key'=>$internalKey,
                            ];
                        }
                    }
                }
            }
        }

        return $map;
    }
}
