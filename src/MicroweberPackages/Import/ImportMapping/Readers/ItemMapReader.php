<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

class ItemMapReader
{
    public static $map = [
        'external_id'=>['id','g:id'],
        'title'=>['title','g:title','name'],
        'content_body'=>['description','content','html'],
        'image'=>['image','g:image_link'],
        'price'=>['price','g:price'],
        'category'=>['category','g:google_product_category'],
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
