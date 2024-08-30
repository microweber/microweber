<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/12/2019
 * Time: 1:52 PM
 */
use MicroweberPackages\Multilanguage\MultilanguageApi;

api_expose_admin('multilanguage/supported_locale/set_active', function ($params) {

    if (isset($params['id'])) {

        $getLocale = get_supported_locale_by_id($params['id']);
        if ($getLocale) {

            $localeUpdate = [];
            $localeUpdate['id'] = $getLocale['id'];

            $localeUpdate['is_active'] = 'n';
            if ($params['is_active'] == 'true') {
                $localeUpdate['is_active'] = 'y';
            }

            $save = db_save('multilanguage_supported_locales', $localeUpdate);
            cache_clear('options');
            cache_clear('content');
            cache_clear('repositories');
            clearcache();
            if ($save) {
                return ['success'=>true];
            }
        }

    }

    return ['error'=>true];

});

api_expose_admin('multilanguage/edit_locale', function ($params) {

    if (isset($params['locale_id'])) {

        $getLocale = get_supported_locale_by_id($params['locale_id']);
        if ($getLocale) {

            $localeUpdate = [];
            $localeUpdate['id'] = $getLocale['id'];
            $localeUpdate['display_name'] = $params['display_name'];
            $localeUpdate['display_icon'] = $params['display_icon'];
            $localeUpdate['display_locale'] = $params['display_locale'];

            $save = db_save('multilanguage_supported_locales', $localeUpdate);
            if ($save) {
                return ['success'=>true];
            }
        }

    }

    return ['error'=>true];
});

api_expose_admin('multilanguage/active_language', function ($params) {

    $api = new MultilanguageApi();
    return $api->activateLanguage($params);

});


api_expose_admin('multilanguage/delete_language', function ($params) {

    $api = new MultilanguageApi();
    return $api->deleteLanguage($params);

});

api_expose_admin('multilanguage/sort_language', function ($params) {

    $api = new MultilanguageApi();
    return $api->sortLanguage($params);

});

api_expose_admin('multilanguage/add_language', function ($params) {

    $api = new MultilanguageApi();
    return $api->addLanguage($params);

});


