<?php

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

if (!function_exists('run_translate_manager')) {
    function run_translate_manager()
    {
        $currentLocale = mw()->lang_helper->current_lang();
        if (is_lang_correct($currentLocale)) {
            $translate = new \MicroweberPackages\Multilanguage\TranslateManager();
            $translate->run();
        }
    }
}

if (!function_exists('get_rel_id_by_multilanguage_url')) {
    function get_rel_id_by_multilanguage_url($url, $relType = false)
    {

        if (!$url) {
            return false;
        }

        $filter = array();
        $filter['field_name'] = 'url';
        $filter['field_value'] = $url;
        $filter['limit'] = 1;
        $filter['single'] = 1;
         $filter['fields'] = ['rel_id', 'field_name','field_value','rel_type'];;
        if ($relType) {
            $filter['rel_type'] = $relType;
        }

        $get = db_get('multilanguage_translations', $filter);
        if ($get) {
            return $get['rel_id'];
        }

        if ($relType == 'categories') {

            $category = get_categories('fields=id,url&url=' . $url . '&single=1&limit=1');
            if ($category) {
                return $category['id'];
            }
            return false;
        }

        $content = get_content('fields=id,url&url=' . $url . '&single=1&limit=1');
        if ($content) {
            return $content['id'];
        }

        return false;
    }
}
if (!function_exists('get_supported_locale_by_id')) {
    function get_supported_locale_by_id($id)
    {
        return db_get('multilanguage_supported_locales', 'id=' . $id . '&single=1');
    }
}
if (!function_exists('get_supported_locale_by_locale')) {

    function get_supported_locale_by_locale($locale)
    {
        return db_get('multilanguage_supported_locales', 'locale=' . $locale . '&single=1');
    }
}
if (!function_exists('get_short_abr')) {

    function get_short_abr($locale)
    {
        if (strlen($locale) == 2) {
            return $locale;
        }

        $exp = explode("_", $locale);

        return strtolower($exp[0]);
    }
}

if (!function_exists('change_language_by_locale')) {
    function change_language_by_locale($locale, $set_cookie = true)
    {
        $isCli = is_cli();

        if (get_option('dusk_test', 'dusk') == 1) {
            $isCli = false;
        }

        if (!$isCli and $set_cookie) {
            $skip = false;

            $cookie = \Cookie::get('lang');

            if ($cookie and $cookie == $locale) {
                $skip = true;
            }

            if (!$skip) {


                $multilanguageIsActive = MultilanguageHelpers::multilanguageIsEnabled();
                if ($multilanguageIsActive) {

                    $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($locale);
                     if (!$localeSettings) {
                        $localeSettings = app()->multilanguage_repository->getSupportedLocaleByDisplayLocale($locale);
                    }

                } else {
                    $localeSettings = ['locale' => $locale];
                }


                $applyCookieLang = true;
                if ($localeSettings != null and isset($localeSettings['locale'])) {
                    if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == $locale) {
                        $applyCookieLang = false;
                    }
                }

                if ($applyCookieLang) {

                    if ($localeSettings != null and isset($localeSettings['locale'])) {

                        setcookie('lang', $locale, time() + (86400 * 30), "/");
                        $_COOKIE['lang'] = $locale;
                        \Cookie::queue('lang', $locale, 86400 * 30);

                        if (isset($localeSettings['display_locale']) and $localeSettings['display_locale']) {
                            setcookie('lang_display', $localeSettings['display_locale'], time() + (86400 * 30), "/");
                            $_COOKIE['lang_display'] = $localeSettings['display_locale'];
                            \Cookie::queue('lang_display', $localeSettings['display_locale'], 86400 * 30);
                        }

                    }
                }
            }
        }

        return app()->lang_helper->set_current_lang($locale);
    }
}
if (!function_exists('add_supported_language')) {
    function add_supported_language($locale, $language)
    {
        $findSupportedLocales = \MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales::where('locale', $locale)->first();
        if ($findSupportedLocales == null) {
            $findSupportedLocales = new \MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales();
            $findSupportedLocales->locale = $locale;
            $findSupportedLocales->language = $language;
            $findSupportedLocales->is_active = 'y';
            $position = 1;
            $getLastLagnuage = \DB::table('multilanguage_supported_locales')->orderBy('position', 'desc')->first();
            if ($getLastLagnuage != null) {
                $position = $getLastLagnuage->position + 1;
            }

            $findSupportedLocales->position = $position;
        }

        $findSupportedLocales->save();

        clearcache();

        return $findSupportedLocales->id;
    }
}
if (!function_exists('get_default_language')) {
    function get_default_language()
    {
        $langs = mw()->lang_helper->get_all_lang_codes();

        $defaultLang = mw()->lang_helper->default_lang();
        $defaultLang = strtolower($defaultLang);

        if (isset($langs[$defaultLang])) {
            return array('locale' => $defaultLang, 'language' => $langs[$defaultLang]);
        }

        $locale = app()->getLocale();
        $locale = strtolower($locale);
        if (isset($langs[$locale])) {
            return array('locale' => $locale, 'language' => $langs[$locale]);
        }

        return false;
    }
}
if (!function_exists('insert_default_language')) {

    function insert_default_language()
    {
        $defaultLang = get_default_language();
        if ($defaultLang) {
            return add_supported_language($defaultLang['locale'], $defaultLang['language']);
        }

        return false;
    }
}
if (!function_exists('is_lang_supported')) {
    function is_lang_supported($lang)
    {

        if (!is_lang_correct($lang)) {
            return false;
        }

        $supportedLanguagesMap = array();
        $supportedLanguages = get_supported_languages();

        foreach ($supportedLanguages as $language) {
            if (!empty($language['display_locale'])) {
                $supportedLanguagesMap[] = $language['display_locale'];
            } else {
                $supportedLanguagesMap[] = $language['locale'];
            }
        }

        return in_array($lang, $supportedLanguagesMap);
    }
}
if (!function_exists('is_lang_correct')) {

    function is_lang_correct($lang)
    {
        $correct = false;
        $langs = mw()->lang_helper->get_all_lang_codes();
        if (is_string($lang) && array_key_exists($lang, $langs)) {
            $correct = true;
        }
        return $correct;
    }
}
if (!function_exists('is_lang_correct_by_display_locale')) {
    function is_lang_correct_by_display_locale($locale)
    {
        $getLocale = app()->multilanguage_repository->getSupportedLocaleByLocale($locale);
        if (!empty($getLocale)) {
            return true;
        }

        return false;
    }
}
if (!function_exists('detect_lang_from_url')) {
    function detect_lang_from_url($targetUrl)
    {
        $targetUrl = str_replace(site_url(), false, $targetUrl);

        $targetLang = mw()->lang_helper->current_lang();
        $segments = explode('/', $targetUrl);

        if (count($segments) > 1) {
            // Find target lang in segments
            $findedLangAbr = false;
            $urlSegs = [];
            foreach ($segments as $segment) {
                if (is_lang_correct($segment)) {
                    if ($findedLangAbr) {
                        $urlSegs[] = $segment;
                    } else {
                        $findedLangAbr = $segment;
                    }
                } else {
                    $urlSegs[] = $segment;
                }
            }
            if ($findedLangAbr) {
                $targetUrl = implode('/', $urlSegs);
                return array('target_lang' => $findedLangAbr, 'target_url' => $targetUrl);
            }
        }

        return array('target_lang' => $targetLang, 'target_url' => $targetUrl);
    }
}

if (!function_exists('get_supported_languages')) {

    function get_supported_languages($only_active = false)
    {
        $languages = app()->multilanguage_repository->getSupportedLocales($only_active);

        if (!empty($languages)) {
            foreach ($languages as &$language) {
                $language['icon'] = get_flag_icon($language['locale']);
                if (empty($language['display_name'])) {
                    $language['language'] = \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($language['locale']);
                }
            }
        }

        return $languages;
    }
}
if (!function_exists('get_country_language_by_country_code')) {

    function get_country_language_by_country_code($country_code)
    {
        // Locale list taken from:
        // http://stackoverflow.com/questions/3191664/
        // list-of-all-locales-and-their-short-codes
        $locales = array(
            'af-ZA',
            'am-ET',
            'ar-AE',
            'ar-BH',
            'ar-DZ',
            'ar-EG',
            'ar-IQ',
            'ar-JO',
            'ar-KW',
            'ar-LB',
            'ar-LY',
            'ar-MA',
            'arn-CL',
            'ar-OM',
            'ar-QA',
            'ar-SA',
            'ar-SY',
            'ar-TN',
            'ar-YE',
            'as-IN',
            'az-Cyrl-AZ',
            'az-Latn-AZ',
            'ba-RU',
            'be-BY',
            'bg-BG',
            'bn-BD',
            'bn-IN',
            'bo-CN',
            'br-FR',
            'bs-Cyrl-BA',
            'bs-Latn-BA',
            'ca-ES',
            'co-FR',
            'cs-CZ',
            'cy-GB',
            'da-DK',
            'de-AT',
            'de-CH',
            'de-DE',
            'de-LI',
            'de-LU',
            'dsb-DE',
            'dv-MV',
            'el-GR',
            'en-029',
            'en-AU',
            'en-BZ',
            'en-CA',
            'en-GB',
            'en-IE',
            'en-IN',
            'en-JM',
            'en-MY',
            'en-NZ',
            'en-PH',
            'en-SG',
            'en-TT',
            'en-US',
            'en-ZA',
            'en-ZW',
            'es-AR',
            'es-BO',
            'es-CL',
            'es-CO',
            'es-CR',
            'es-DO',
            'es-EC',
            'es-ES',
            'es-GT',
            'es-HN',
            'es-MX',
            'es-NI',
            'es-PA',
            'es-PE',
            'es-PR',
            'es-PY',
            'es-SV',
            'es-US',
            'es-UY',
            'es-VE',
            'et-EE',
            'eu-ES',
            'fa-IR',
            'fi-FI',
            'fil-PH',
            'fo-FO',
            'fr-BE',
            'fr-CA',
            'fr-CH',
            'fr-FR',
            'fr-LU',
            'fr-MC',
            'fy-NL',
            'ga-IE',
            'gd-GB',
            'gl-ES',
            'gsw-FR',
            'gu-IN',
            'ha-Latn-NG',
            'he-IL',
            'hi-IN',
            'hr-BA',
            'hr-HR',
            'hsb-DE',
            'hu-HU',
            'hy-AM',
            'id-ID',
            'ig-NG',
            'ii-CN',
            'is-IS',
            'it-CH',
            'it-IT',
            'iu-Cans-CA',
            'iu-Latn-CA',
            'ja-JP',
            'ka-GE',
            'kk-KZ',
            'kl-GL',
            'km-KH',
            'kn-IN',
            'kok-IN',
            'ko-KR',
            'ky-KG',
            'lb-LU',
            'lo-LA',
            'lt-LT',
            'lv-LV',
            'mi-NZ',
            'mk-MK',
            'ml-IN',
            'mn-MN',
            'mn-Mong-CN',
            'moh-CA',
            'mr-IN',
            'ms-BN',
            'ms-MY',
            'mt-MT',
            'nb-NO',
            'ne-NP',
            'nl-BE',
            'nl-NL',
            'nn-NO',
            'nso-ZA',
            'oc-FR',
            'or-IN',
            'pa-IN',
            'pl-PL',
            'prs-AF',
            'ps-AF',
            'pt-BR',
            'pt-PT',
            'qut-GT',
            'quz-BO',
            'quz-EC',
            'quz-PE',
            'rm-CH',
            'ro-RO',
            'ru-RU',
            'rw-RW',
            'sah-RU',
            'sa-IN',
            'se-FI',
            'se-NO',
            'se-SE',
            'si-LK',
            'sk-SK',
            'sl-SI',
            'sma-NO',
            'sma-SE',
            'smj-NO',
            'smj-SE',
            'smn-FI',
            'sms-FI',
            'sq-AL',
            'sr-Cyrl-BA',
            'sr-Cyrl-CS',
            'sr-Cyrl-ME',
            'sr-Cyrl-RS',
            'sr-Latn-BA',
            'sr-Latn-CS',
            'sr-Latn-ME',
            'sr-Latn-RS',
            'sv-FI',
            'sv-SE',
            'sw-KE',
            'syr-SY',
            'ta-IN',
            'te-IN',
            'tg-Cyrl-TJ',
            'th-TH',
            'tk-TM',
            'tn-ZA',
            'tr-TR',
            'tt-RU',
            'tzm-Latn-DZ',
            'ug-CN',
            'uk-UA',
            'ur-PK',
            'uz-Cyrl-UZ',
            'uz-Latn-UZ',
            'vi-VN',
            'wo-SN',
            'xh-ZA',
            'yo-NG',
            'zh-CN',
            'zh-HK',
            'zh-MO',
            'zh-SG',
            'zh-TW',
            'zu-ZA',);

        $country_code = strtolower($country_code);

        foreach ($locales as $locale) {
            $locale = strtolower($locale);
            $locale_exp = explode('-', $locale);

            if ($locale_exp[1] == $country_code) {
                return $locale_exp[0];
            }
        }

        return false;
    }
}
if (!function_exists('get_geolocation_detailed')) {


    function get_geolocation_detailed()
    {
        $ip = user_ip();
        // $ip = '8.8.8.8';

        $geolocationProvider = get_option('geolocation_provider', 'multilanguage_settings');
        $accessKey = get_option('ipstack_api_access_key', 'multilanguage_settings');

        if ($geolocationProvider == 'ipstack_com') {
            $ipInfo = mw()->http->url('http://api.ipstack.com/' . $ip . '?access_key=' . $accessKey)->get();
        } else if ($geolocationProvider == 'domain_detection') {
            $countryCode = get_country_code_from_domain();
            $ipInfo = json_encode(array(
                'countryCode' => $countryCode
            ));
        } else if ($geolocationProvider == 'browser_detection') {
            $browserCountryCode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 3, 2);
            $ipInfo = json_encode(array(
                'countryCode' => $browserCountryCode
            ));
        } else if ($geolocationProvider == 'geoip_browser_detection') {

            $defaultWebsiteLanguage = mw()->lang_helper->default_lang();
            $browserCountryCode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 3, 2);
            $browserLanguage = get_country_language_by_country_code($browserCountryCode);

            if ($defaultWebsiteLanguage == $browserLanguage) {
                $ipInfo = json_encode(array(
                    'countryCode' => $browserCountryCode
                ));
            }

            if ($defaultWebsiteLanguage !== $browserLanguage) {
                $ipInfo = mw()->http->url('http://ipinfo.microweberapi.com/?ip=' . $ip)->get();
            }

        } else {
            $ipInfo = mw()->http->url('http://ipinfo.microweberapi.com/?ip=' . $ip)->get();
        }

        $geoLocation = json_decode($ipInfo, true);
        // $geoLocation['provider'] = $geolocationProvider;

        return $geoLocation;
    }
}

if (!function_exists('get_country_code_from_domain')) {

    function get_country_code_from_domain()
    {
        $countryCode = 'EN';
        if (strpos($_SERVER['HTTP_HOST'], '.com') !== false) {
            $countryCode = 'EN';
        }

        if (strpos($_SERVER['HTTP_HOST'], '.bg') !== false) {
            $countryCode = 'BG';
        }

        if (strpos($_SERVER['HTTP_HOST'], '.ru') !== false) {
            $countryCode = 'RU';
        }

        if (strpos($_SERVER['HTTP_HOST'], '.gr') !== false) {
            $countryCode = 'GR';
        }

        if (strpos($_SERVER['HTTP_HOST'], '.ar') !== false) {
            $countryCode = 'AR';
        }

        return $countryCode;
    }
}
if (!function_exists('get_geolocation')) {

    function get_geolocation()
    {
        $geoLocation = get_geolocation_detailed();

        $countryCode = false;

        if (isset($geoLocation['country_code'])) {
            $countryCode = $geoLocation['country_code'];
        }

        if (isset($geoLocation['countryCode'])) {
            $countryCode = $geoLocation['countryCode'];
        }

        if ($countryCode) {
            return array('countryCode' => $countryCode);
        }

        return false;
    }
}
if (!function_exists('multilanguage_get_all_links')) {

    function multilanguage_get_all_links()
    {
        $generator = new \MicroweberPackages\Multilanguage\MultilanguageLinksGenerator();

        return $generator->links();
    }
}
if (!function_exists('multilanguage_get_all_content_links')) {
    function multilanguage_get_all_content_links($contentId = false)
    {
        $generator = new \MicroweberPackages\Multilanguage\MultilanguageLinksGenerator();

        return $generator->contentLinks($contentId);
    }
}
if (!function_exists('multilanguage_get_all_post_links')) {

    function multilanguage_get_all_post_links($postId = false)
    {
        $generator = new \MicroweberPackages\Multilanguage\MultilanguageLinksGenerator();

        return $generator->postLinks($postId);
    }
}
if (!function_exists('multilanguage_get_all_product_links')) {

    function multilanguage_get_all_product_links($productId = false)
    {
        $generator = new \MicroweberPackages\Multilanguage\MultilanguageLinksGenerator();

        return $generator->productLinks($productId);
    }
}
if (!function_exists('multilanguage_get_all_category_links')) {

    function multilanguage_get_all_category_links($categoryId = false)
    {
        $generator = new \MicroweberPackages\Multilanguage\MultilanguageLinksGenerator();

        return $generator->categoryLinks($categoryId);
    }
}
if (!function_exists('is_active_lang')) {

    function is_active_lang($url)
    {
        $res = false;

        $activeLangs = get_supported_languages(true);

        if (is_array($activeLangs)) {
            foreach ($activeLangs as $activeLang) {
                if ($activeLang['display_locale'] == $url) {
                    $res = true;
                    break;
                }
            }
        }

        return $res;
    }
}
if (!function_exists('get_display_locale_by_locale')) {

//Transform en_uk to en
    function get_display_locale_by_locale($locale)
    {
        $res = '';
        $activeLangs = get_supported_languages(true);

        if (is_array($activeLangs)) {
            foreach ($activeLangs as $activeLang) {
                if ($activeLang['locale'] == $locale) {
                    $res = $activeLang['display_locale'];
                }
            }
        }

        return $res;
    }
}
