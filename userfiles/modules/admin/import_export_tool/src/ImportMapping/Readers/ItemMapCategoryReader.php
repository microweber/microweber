<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class ItemMapCategoryReader extends ItemMapReader
{
    public static $map = [
        'id' => ['id'],
    ];

    public static $itemTypes = [];

    private static $itemNames = [
        'id' => 'Id',
        'title' => 'Title',
        'updated_at' => 'Updated at',
        'created_at' => 'Created at',
    ];

    private static $itemGroups = [];

    public static function getItemNames()
    {
        $itemNames = self::$itemNames;

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            foreach (get_supported_languages() as $language) {
                $itemNames['multilanguage.title.' . $language['locale']] = 'Title ['. $language['locale'].']';
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
            }
            $itemGroups['Multilanguage'] = $itemGroupMultilanguage;
        }

        return $itemGroups;
    }
}
