<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/13/2019
 * Time: 2:29 PM
 */
namespace MicroweberPackages\Multilanguage;

use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;

class MultilanguageApi
{
    public function activateLanguage($params)
    {
        if (isset($params['active'])) {
            if ($params['active'] == 'true') {
                $defaultLang = mw()->lang_helper->default_lang();
                $findDefaultLangInSupportedLocales = MultilanguageSupportedLocales::where('locale', $defaultLang)->first();
                if ($findDefaultLangInSupportedLocales == null) {
                    add_supported_language($defaultLang, $defaultLang);
                }
                save_option('is_active','y','multilanguage_settings');
            } else {
                save_option('is_active','n','multilanguage_settings');
            }

            \Cookie::queue(\Cookie::forget('back_to_admin'));


            // clearcache();
        }
    }

    public function deleteLanguage($params)
    {
        if (isset($params['id'])) {

            $get = array();
            $get['id'] = intval($params['id']);
            $get['single'] = true;
            $get['no_cache'] = true;

            $find = db_get('multilanguage_supported_locales', $get);

            if ($find) {
                $delete = db_delete('multilanguage_supported_locales', $find['id']);

                TranslationText::where('translation_locale', $find['locale'])->delete();

                clearcache();
                return $delete;
            }
        }
    }

    public function sortLanguage($params) {
        if (isset($params['ids'])) {
            if (is_array($params['ids']) && !empty($params['ids'])) {
                foreach ($params['ids'] as $id) {
                    if (isset($id['id']) && isset($id['position']) && !empty($id['id']) && !empty($id['position'])) {
                        $save = array();
                        $save['id'] = $id['id'];
                        $save['position'] = $id['position'];
                        db_save('multilanguage_supported_locales', $save);
                    }
                }
                clearcache();
            }
        }
    }

    public function addLanguage($params) {

        if (isset($params['locale']) && isset($params['language'])) {

            $locale = $params['locale'];
            $language = $params['language'];

            $add = add_supported_language($locale, $language);

            TranslationPackageInstallHelper::installLanguage($locale);

            clearcache();
            return $add;
        }

        return false;
    }

    public function changeLanguage($params) {

        if (!isset($params['locale'])) {
            return;
        }



        $json = array();
        $locale = $params['locale'];
        if (!is_lang_correct($locale)) {
            return array('error' => _e('Locale is not supported', true));
        }

        if (!MultilanguageHelpers::multilanguageIsEnabled()) {
            $mlPermalink = new PermalinkManager();
            change_language_by_locale($locale, true);
        } else {
            $mlPermalink = new MultilanguagePermalinkManager($locale);
            change_language_by_locale($locale, true);
            run_translate_manager();
        }



        if (isset($params['is_admin']) && $params['is_admin'] == 1) {
            mw()->event_manager->trigger('mw.admin.change_language');
            $json['refresh'] = true;
            return $json;
        } else {

            $url = url_current(true);
            $location = false;

            $categoryId = get_category_id_from_url($url);
            $contentId = mw()->content_manager->get_content_id_from_url($url);
            $contentCheck = get_content_by_id($contentId);



            if ($contentCheck && isset($contentCheck['content_type'])) {
                if ($categoryId && $contentCheck['content_type'] == 'page') {
                    $location = $mlPermalink->link($categoryId, 'category');
                }
                if ($categoryId && $contentCheck['content_type'] != 'page') {
                    $location = $mlPermalink->link($contentId, 'content');
                }
                if (($categoryId == false || $categoryId == 0) && $contentCheck['content_type'] == 'page') {
                    $location = $mlPermalink->link($contentId, 'content');
                }
                if (($categoryId == false || $categoryId == 0) && $contentCheck['content_type'] != 'page') {
                    $location = $mlPermalink->link($contentId, 'content');
                }
            }

            if ($contentCheck == false && $categoryId > 0) {
                $location = $mlPermalink->link($categoryId, 'category');
            }

            if  ($location){
                $json['location'] = $location;
            }
        }

        $json['refresh'] = true;

        return $json;
    }
}
