<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/13/2019
 * Time: 2:29 PM
 */

class MultilanguageApi
{
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
        $localeSettings = get_supported_locale_by_locale($locale);

        /*
        if (!empty($localeSettings['display_locale'])) {
            $locale = $localeSettings['display_locale'];
        }*/

        if (!is_lang_correct($locale)) {
            return array('error' => _e('Locale is not supported', true));
        }

        $mlPermalink = new MultilanguagePermalinkManager($locale);

        change_language_by_locale($locale);
        run_translate_manager();

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

        /*  var_dump([
                'categoryId'=> $categoryId,
                'contentId'=>$contentId,
                'contentCheck'=>$contentCheck
            ]);*/


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
