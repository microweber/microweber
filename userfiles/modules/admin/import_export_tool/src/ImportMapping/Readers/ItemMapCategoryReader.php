<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class ItemMapCategoryReader extends ItemMapReader
{
    public static $map = [
        'id' => ['id'],
        'updated_at' => ['updated_date', 'published','updated_at'],
        'created_at' => ['publish_date', 'pubDate', 'updated','created_at'],
        'is_hidden' => ['isEnable', 'isEnabled', 'isActive'],
    ];

    public static $itemTypes = [];

    private static $itemNames = [
        'id' => 'Id',
        'parent_id' => 'Parent Id',
        'title' => 'Title',
        'description' => 'Description',
        'image' => 'Image',
        'category_meta_title' => 'Meta Title',
        'category_meta_keywords' => 'Meta Keywords',
        'category_meta_description' => 'Meta Description',
        'updated_at' => 'Updated at',
        'created_at' => 'Created at',
        'is_hidden' => 'Active',
    ];

    private static $itemGroups = [
        'Content Fields'=> [
            'content_fields.*'
        ]
    ];


    public static function getItemNames()
    {
        $itemNames = self::$itemNames;

        $templateConfig = mw()->template->get_config();

        $editFieldsCategory = [];
        if (isset($templateConfig['edit-fields-category']) && !empty($templateConfig['edit-fields-category'])) {
            foreach ($templateConfig['edit-fields-category'] as $templateField) {
                $itemNames['content_fields.' . $templateField['name']] = $templateField['title'];
                $editFieldsCategory['content_fields.' . $templateField['name']] = $templateField['title'];
            }
        }

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            foreach (get_supported_languages() as $language) {

                $itemNames['multilanguage.title.' . $language['locale']] = 'Title ['. $language['locale'].']';
                $itemNames['multilanguage.description.' . $language['locale']] = 'Description ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_title.' . $language['locale']] = 'Meta Title ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_keywords.' . $language['locale']] = 'Meta Keywords ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_description.' . $language['locale']] = 'Meta Description ['. $language['locale'].']';

                if (!empty($editFieldsCategory)) {
                    foreach ($editFieldsCategory as $editField=>$titleField) {
                        $itemNames['multilanguage.'.$editField.'.' . $language['locale']] = $titleField . ' ['. $language['locale'].']';
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

            foreach (get_supported_languages() as $language) {

                $itemGroupMultilanguage = [];
                $itemGroupMultilanguage[] = 'multilanguage.title.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.description.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.category_meta_title.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.category_meta_keywords.' . $language['locale'];
                $itemGroupMultilanguage[] = 'multilanguage.category_meta_description.' . $language['locale'];

                if (isset($language['language']) && !empty($language['language'])) {
                    $itemGroups['Multilanguage - ' . $language['language']] = $itemGroupMultilanguage;
                } else {
                    $itemGroups['Multilanguage - ' . $language['locale']] = $itemGroupMultilanguage;
                }

            }
        }

        return $itemGroups;
    }
}
