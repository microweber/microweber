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

            $metaTagsHtml = '';


            // In homepage logis
            if (is_home()) {

                $currentLocale = app()->getLocale();

                $canonicalLink = url_current(true);
                $canonicalLink = urldecode($canonicalLink);
                $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

                foreach ($supportedLanguages as $locale) {
                    $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);
                    $contentLink = $pm->link(CONTENT_ID, 'content');

                    if ($locale['locale'] == $currentLocale) {
                        continue;
                    }

                    $metaLocale = $locale['locale'];
                    $expMetaLocale = explode('_', $metaLocale);
                    if (count($expMetaLocale) > 1) {
                        $metaLocale = $expMetaLocale[0];
                    }

                    $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
                }

                return $metaTagsHtml;
            }

            // In other pages
            $canonicalLink = url_current(true);
            $canonicalLink = urldecode($canonicalLink);
            $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

            foreach ($supportedLanguages as $locale) {
                $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);
                $contentLink = $pm->link(CONTENT_ID, 'content');

                $metaLocale = $locale['locale'];
                $expMetaLocale = explode('_', $metaLocale);
                if (count($expMetaLocale) > 1) {
                    $metaLocale = $expMetaLocale[0];
                }

                $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
            }

            return $metaTagsHtml;
        });
    });
}
