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


$supportedLanguages = get_supported_languages();
if (!empty($supportedLanguages)) {
    event_bind('mw.controller.index', function () use ($supportedLanguages) {
        template_head(function () use ($supportedLanguages) {

            /**
             * Links must look like
             *
                <link rel="canonical" href="https://localhost/microweber/ar_SA/others/apple-computer" />
                <link rel="alternate" href="https://localhost/microweber/bg_BG/others/apple-computer" hreflang="bg-BG" />
                <link rel="alternate" href="https://localhost/microweber/ar_SA/others/apple-computer" hreflang="ar-SA" />
             */

            /**
             * 1.Canonical links is current page url
             */
            $canonicalLink = url_current(true);
            $canonicalLink = urldecode($canonicalLink);

            $metaTagsHtml = '';
            // $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

            $currentLang = mw()->lang_helper->default_lang();
            foreach ($supportedLanguages as $locale) {
                $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);
                $contentLink = $pm->link(CONTENT_ID, 'content');

                $metaLocale = str_replace('_', '-', $locale['locale']);

                $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
            }

            return $metaTagsHtml;
        });
    });
}
