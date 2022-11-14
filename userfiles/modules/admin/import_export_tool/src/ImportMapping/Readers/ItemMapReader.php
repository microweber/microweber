<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class ItemMapReader
{
    public static $categorySeparators = [
        '|', '>', ';', ',', '_'
    ];

    public static $map = [
        'content_data.depth' => ['depth'],
        'content_data.height' => ['height'],
        'content_data.width' => ['width'],
        'content_data.qty' => ['qty','quantity', 'g:qty'],
        'content_data.mpn' => ['mpn', 'g:mpn'],
        'content_data.sku' => ['sku', 'g:sku'],
        'content_data.weight' => ['weight'],
        'content_data.weight_type' => ['weight_type'],
        'content_data.barcode' => ['barcode', 'gtin', 'g:gtin'],
        'content_data.external_id' => ['id', 'g:id'],
        'title' => ['title', 'g:title', 'name'],
        'content_body' => ['description', 'g:description', 'content', 'html', 'summary'],
        'media_urls' => ['image', 'g:image_link'],
        'price' => ['price', 'g:price'],
        'id' => ['id'],
        'parent' => ['parent_id'],
        'content_data.special_price' => ['special_price', 'discount_price'],
        'content_data.shipping_fixed_cost' => ['shipping_price', 'g:shipping.g:price','shipping_fixed_cost'],
        'categories' => ['genre', 'category', 'g:google_product_category'],
        'updated_at' => ['updated_date', 'published','updated_at'],
        'created_at' => ['publish_date', 'pubDate', 'updated','created_at'],
        'is_active' => ['isEnable', 'isEnabled', 'isActive','is_active'],
        'tags' => ['tags'],
    ];

    public static $itemTypes = [
        'media_urls' => self::ITEM_TYPE_ARRAY,
        'categories' => self::ITEM_TYPE_ARRAY,
        'category_ids' => self::ITEM_TYPE_ARRAY,
        'first_level_categories' => self::ITEM_TYPE_ARRAY,
        'tags' => self::ITEM_TYPE_ARRAY,
    ];

    private static $itemNames = [
        'id' => 'Id',
        'parent' => 'Parent Id',
        'content_data.external_id' => 'External Id',
        'title' => 'Title',
        //  'description'=>'Description',
        'content_body' => 'Content Body',
        'media_urls' => 'Pictures',
        'category_ids' => 'Category Ids',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'price' => 'Price',
        'content_data.special_price' => 'Special Price',
        'content_data.qty' => 'Quantity',
        'content_data.shipping_fixed_cost' => 'Shipping Fixed Cost',
        'content_data.width' => 'Width',
        'content_data.height' => 'Height',
        'content_data.depth' => 'Depth',
        'content_data.weight' => 'Weight',
        'content_data.weight_type' => 'Weight Type',
        'content_data.mpn' => 'MPN',
        'content_data.barcode' => 'Barcode',
        'content_data.sku' => 'SKU',
        'is_active' => 'Active',
        'updated_at' => 'Updated at',
        'created_at' => 'Created at',
    ];

    private static $itemGroups = [
        'Content Data'=> [
            'content_data.*',
        ],
        'Content Fields'=> [
            'content_fields.*'
        ]
    ];

    public const ITEM_TYPE_STRING = 'string';
    public const ITEM_TYPE_ARRAY = 'array';

    private static function getTemplateEditFields()
    {
        $templateConfig = mw()->template->get_config();

        $editFieldsProduct = [];
        if (isset($templateConfig['edit-fields-product']) && !empty($templateConfig['edit-fields-product'])) {
            foreach ($templateConfig['edit-fields-product'] as $templateField) {
                $editFieldsProduct['content_fields.' . $templateField['name']] = $templateField['title'];
            }
        }

        return $editFieldsProduct;
    }

    public static function getItemNames()
    {
        $itemNames = self::$itemNames;

        $templateEditFields = self::getTemplateEditFields();
        if (!empty($templateEditFields)) {
            foreach ($templateEditFields as $fieldName => $fieldTitle) {
                $itemNames[$fieldName] = $fieldTitle;
            }
        }

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            foreach (get_supported_languages() as $language) {
                $itemNames['multilanguage.title.' . $language['locale']] = 'Title ['. $language['locale'].']';
                $itemNames['multilanguage.url.' . $language['locale']] = 'Slug ['. $language['locale'].']';
                $itemNames['multilanguage.content_body.' . $language['locale']] = 'Content Body ['. $language['locale'].']';
                $itemNames['multilanguage.content_meta_title.' . $language['locale']] = 'Meta Title ['. $language['locale'].']';
                $itemNames['multilanguage.content_meta_keywords.' . $language['locale']] = 'Meta Keywords ['. $language['locale'].']';
                $itemNames['multilanguage.description.' . $language['locale']] = 'Meta Description ['. $language['locale'].']';

                if (!empty($templateEditFields)) {
                    foreach ($templateEditFields as $fieldName=>$fieldTitle) {
                        $itemNames['multilanguage.'.$fieldName.'.' . $language['locale']] = $fieldTitle . ' ['. $language['locale'].']';
                    }
                }
            }
        }

        return $itemNames;
    }

    public static function getItemGroups()
    {
        $itemGroups = self::$itemGroups;

        if (MultilanguageHelpers::multilanguageIsEnabled()) {

            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)) {

                $templateEditFields = self::getTemplateEditFields();

                foreach ($supportedLanguages as $language) {

                    $itemGroupMultilanguage = [];
                    $itemGroupMultilanguage[] = 'multilanguage.title.' . $language['locale'];
                    $itemGroupMultilanguage[] = 'multilanguage.url.' . $language['locale'];
                    $itemGroupMultilanguage[] = 'multilanguage.description.' . $language['locale'];
                    $itemGroupMultilanguage[] = 'multilanguage.content_body.' . $language['locale'];
                    $itemGroupMultilanguage[] = 'multilanguage.content_meta_title.' . $language['locale'];
                    $itemGroupMultilanguage[] = 'multilanguage.content_meta_keywords.' . $language['locale'];

                    if (isset($language['language']) && !empty($language['language'])) {
                        $itemGroups['Multilanguage - ' . $language['language']] = $itemGroupMultilanguage;
                    } else {
                        $itemGroups['Multilanguage - ' . $language['locale']] = $itemGroupMultilanguage;
                    }
                }

                foreach ($supportedLanguages as $language) {
                    if (!empty($templateEditFields)) {
                        $contentFields = [];
                        foreach ($templateEditFields as $fieldName => $fieldTitle) {
                            $contentFields[] = 'multilanguage.' . $fieldName . '.' . $language['locale'];
                        }
                        if (isset($language['language']) && !empty($language['language'])) {
                            $itemGroups['Multilanguage Content Fields - ' . $language['language']] = $contentFields;
                        } else {
                            $itemGroups['Multilanguage Content Fields - ' . $language['locale']] = $contentFields;
                        }
                    }
                }

            }
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
