<?php
/**
 * Author: Bozhidar Slaveykov
 */


if (defined('MW_DISABLE_MULTILANGUAGE')) {
    return;
}

require_once (__DIR__.'/api_exposes.php');

// Check multilanguage is active
if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') !== 'y') {
    return;
}

// Event binds must be only when multilanguage is active
require_once (__DIR__.'/event_binds_general.php');


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
                $homepageLanguage = get_option('homepage_language', 'website');
                if (empty($homepageLanguage)) {
                    $homepageLanguage = $currentLocale;
                }

                $canonicalLink = url_current(true);
                $canonicalLink = urldecode($canonicalLink);
                $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

                $metaTagsHtml .= '<link rel="alternate" href="' . site_url() . '" hreflang="' . $homepageLanguage . '" />' . "\n";

                foreach ($supportedLanguages as $locale) {
                    $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);
                    $contentLink = $pm->link(content_id(), 'content');
                    $contentLink = trim($contentLink);

                    if (empty($contentLink)) {
                        continue;
                    }
                    if ($homepageLanguage == $locale['locale']) {
                        // Skip this alternate links
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

            $inPage = false;
            $inProduct = false;
            $inCategory = false;
            if (page_id() == content_id()) {
                $inPage = true;
            } else {
                $inProduct = true;
            }

            if ($inPage && category_id() !== 0) {
                $inCategory = true;
            }

            foreach ($supportedLanguages as $locale) {
                $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);

                if ($inCategory) {
                    $contentLink = $pm->link(category_id(), 'category');
                } else {
                    $contentLink = $pm->link(content_id(), 'content');
                }

                if (empty($contentLink)) {
                    continue;
                }

                $metaLocale = $locale['locale'];
                $metaLocale = str_replace('_', '-', $metaLocale);
//                $expMetaLocale = explode('_', $metaLocale);
//                if (count($expMetaLocale) > 1) {
//                    $metaLocale = $expMetaLocale[0];
//                }


                $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
            }

            return $metaTagsHtml;
        });
    });
}
