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

    private static function getTemplateEditFields()
    {
        $templateConfig = mw()->template->get_config();

        $editFieldsCategory = [];
        if (isset($templateConfig['edit-fields-category']) && !empty($templateConfig['edit-fields-category'])) {
            foreach ($templateConfig['edit-fields-category'] as $templateField) {
                $editFieldsCategory['content_fields.' . $templateField['name']] = $templateField['title'];
            }
        }

        return $editFieldsCategory;
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
                $itemNames['multilanguage.description.' . $language['locale']] = 'Description ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_title.' . $language['locale']] = 'Meta Title ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_keywords.' . $language['locale']] = 'Meta Keywords ['. $language['locale'].']';
                $itemNames['multilanguage.category_meta_description.' . $language['locale']] = 'Meta Description ['. $language['locale'].']';

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

                    $itemFields = [];
                    $itemFields[] = 'multilanguage.title.' . $language['locale'];
                    $itemFields[] = 'multilanguage.description.' . $language['locale'];
                    $itemFields[] = 'multilanguage.category_meta_title.' . $language['locale'];
                    $itemFields[] = 'multilanguage.category_meta_keywords.' . $language['locale'];
                    $itemFields[] = 'multilanguage.category_meta_description.' . $language['locale'];

                    if (isset($language['language']) && !empty($language['language'])) {
                        $itemGroups['Multilanguage - ' . $language['language']] = $itemFields;
                    } else {
                        $itemGroups['Multilanguage - ' . $language['locale']] = $itemFields;
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
}
