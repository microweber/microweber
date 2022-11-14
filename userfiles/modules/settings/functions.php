<?php

/**
 *
 * Settings module api
 *
 * @package     modules
 * @subpackage      settings
 * @since       Version 0.1
 */

// ------------------------------------------------------------------------
use Illuminate\Http\Request;

event_bind('admin_header_menu', 'mw_print_admin_menu_settings_btn');

function mw_print_admin_menu_settings_btn()
{
    $active = mw()->url_manager->param('view');
    $cls = '';
    if ($active == 'settings') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:settings" title="' . _e("Settings", true) . '"><i class="ico inavsettings"></i><span>' . _e("Settings", true) . '</span></a></li>';
}

api_expose('apply_change_language', function ($params) {

    if (isset($params['lang']) and $params['lang']) {

        $locale = $params['lang'];

        setcookie('lang', $locale, time() + (86400 * 30), "/");
        $_COOKIE['lang'] = $locale;
        \Cookie::queue('lang', $locale, 86400 * 30);
        $msg = true;
        if (isset($params['import_language_if_not_imported'])) {

            $getTranslationLocalesImported =  app()->translation_key_repostory->getImportedLocales($params['lang']);
            if(empty($getTranslationLocalesImported)){
                $importLanguage = \MicroweberPackages\Translation\TranslationPackageInstallHelper::installLanguage($params['lang']);
                if(isset($importLanguage['success'])){
                    $msg = $importLanguage['success'];
                }
             }
         }

        return ['success'=>$msg,   'lang'=>mw()->lang_helper->set_current_lang($locale)];
    }

});
