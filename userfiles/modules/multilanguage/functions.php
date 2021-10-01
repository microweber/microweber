<?php
/**
 * Author: Bozhidar Slaveykov
 */


if (defined('MW_DISABLE_MULTILANGUAGE')) {
    return;
}

require_once 'api_exposes.php';

// Check multilanguage is active
if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') !== 'y') {
    return;
}

// Event binds must be only when multilanguage is active
require_once 'event_binds_general.php';

event_bind('mw.controller.index', function () {
    template_head(function () {

        $currentLang = mw()->lang_helper->default_lang();




        $link = '';


        $supportedLanguages = get_supported_languages();
        if ($supportedLanguages) {
            foreach ($supportedLanguages as $locale) {


                $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);

                $content_link = $pm->link(CONTENT_ID, 'content');

                if ($currentLang == $locale['locale']) {
                    $locale['locale'] = 'x-default';
                }

                $locale['locale'] = str_replace('_', '-', $locale['locale']);
                if ($content_link) {
                    if ($locale['locale'] == 'x-default') {
                        if (is_home()) {
                            $content_link = site_url();
                        } else {
                            $content_link = url_current(1);
                        }
                        $link .= '<link rel="canonical" href="' . $content_link . '" />' . "\n";
                        $link .= '<link rel="alternate" href="' . $content_link . '" hreflang="' . $locale['locale'] . '" />' . "\n";

                    } else {
                        $link .= '<link rel="alternate" href="' . $content_link . '" hreflang="' . $locale['locale'] . '" />' . "\n";

                    }

                }
            }
        } else {
            $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager(app()->getLocale());

            $content_link = $pm->link(CONTENT_ID, 'content');

            if (defined('IS_HOME') and IS_HOME) {
                $content_link = site_url();
            }
            if (is_category()) {
                $content_link = $pm->link(CATEGORY_ID, 'category');
            }
            if (is_post()) {
                $content_link = $pm->link(CONTENT_ID, 'content');
            }



            $link = '<link rel="canonical" href="' . $content_link . '" />' . "\n";

        }
        return $link;
    });
});
