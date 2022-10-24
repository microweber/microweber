<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class ItemMapReader
{
    public static $categorySeparators = [
        ' | ', '|', ' > ', '>', ' ; ', ';', ' , ', ',', ' _ ', '_'
    ];

    public static $map = [
        'content_data.mpn' => ['mpn', 'g:mpn'],
        'content_data.sku' => ['sku', 'g:sku'],
        'content_data.weight' => ['weight'],
        'content_data.barcode' => ['barcode', 'gtin', 'g:gtin'],
        'content_data.external_id' => ['id', 'g:id'],
        'title' => ['title', 'g:title', 'name'],
        'content_body' => ['description', 'g:description', 'content', 'html', 'summary'],
        'pictures' => ['image', 'g:image_link'],
        'price' => ['price', 'g:price'],
        'content_data.special_price' => ['special_price', 'discount_price'],
        'content_data.shipping_fixed_cost' => ['shipping_price', 'g:shipping.g:price'],
        'categories' => ['genre', 'category', 'g:google_product_category'],
        'updated_at' => ['updated_date', 'published','updated_at'],
        'created_at' => ['publish_date', 'pubDate', 'updated','created_at'],
        'is_active' => ['isEnable', 'isEnabled', 'isActive'],
    ];

    public static $itemTypes = [
        'pictures' => self::ITEM_TYPE_ARRAY,
        'categories' => self::ITEM_TYPE_ARRAY,
        'first_level_categories' => self::ITEM_TYPE_ARRAY,
        'tags' => self::ITEM_TYPE_ARRAY,
    ];

    private static $itemNames = [
        'content_data.external_id' => 'External ID',
        'title' => 'Title',
        //  'description'=>'Description',
        'content_body' => 'Content Body',
        'pictures' => 'Pictures',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'price' => 'Price',
        'is_active' => 'Active',
        'content_data.special_price' => 'Special Price',
        'content_data.shipping_fixed_cost' => 'Shipping Fixed Cost',
        'content_data.weight' => 'Weight',
        'content_data.mpn' => 'MPN',
        'content_data.barcode' => 'Barcode',
        'content_data.sku' => 'SKU',
        'updated_at' => 'Updated at',
        'created_at' => 'Created at',
    ];

    private static $itemGroups = [
        'Content Data'=> [
            'content_data.special_price',
            'content_data.shipping_fixed_cost',
            'content_data.weight',
            'content_data.mpn',
            'content_data.barcode',
            'content_data.sku',
        ]
    ];

    public const ITEM_TYPE_STRING = 'string';
    public const ITEM_TYPE_ARRAY = 'array';

    public static function getItemNames()
    {
        $itemNames = self::$itemNames;

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            foreach (get_supported_languages() as $language) {
                $itemNames['multilanguage.title.' . $language['locale']] = 'Title ['. $language['locale'].']';
                $itemNames['multilanguage.slug.' . $language['locale']] = 'Slug ['. $language['locale'].']';
                $itemNames['multilanguage.content_body.' . $language['locale']] = 'Content Body ['. $language['locale'].']';
                $itemNames['multilanguage.content_meta_title.' . $language['locale']] = 'Meta Title ['. $language['locale'].']';
                $itemNames['multilanguage.content_meta_keywords.' . $language['locale']] = 'Meta Keywords ['. $language['locale'].']';
                $itemNames['multilanguage.description.' . $language['locale']] = 'Meta Description ['. $language['locale'].']';
            }
        }

        return $itemNames;
    }

    public static function getItemGroups()
    {
        $itemGroups = self::$itemGroups;

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $itemGroupMultilanguage = [];
            foreach (get_supported_languages() as $language) {
                $itemGroupMultilanguage[] = 'multilanguage.title.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.slug.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.description.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.content_body.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.content_meta_title.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.content_meta_keywords.' . $language['locale'];
            }
            $itemGroups['Multilanguage'] = $itemGroupMultilanguage;
        }

        return $itemGroups;
    }

    public static function getMapping($item)
    {
        $map = [];
        if (!empty($item)) {
            foreach ($item as $itemKey => $itemValue) {
                foreach (self::$map as $internalKey => $mapKeys) {
                    foreach ($mapKeys as $mapKey) {
                        if ($itemKey == $mapKey) {

                            $itemType = self::ITEM_TYPE_STRING;
                            if (is_array($itemValue)) {
                                $itemType = self::ITEM_TYPE_ARRAY;
                            }

                            $map[$itemKey] = [
                                'item_key' => $itemKey,
                                'item_type' => $itemType,
                                'internal_key' => $internalKey,
                            ];
                        }
                    }
                }
            }
        }

        return $map;
    }
}
