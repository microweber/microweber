<?php

namespace MicroweberPackages\Security;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerReference;
use voku\helper\AntiXSS;

class XSSClean
{
    public function cleanArray($array)
    {
        if (is_array($array)) {
            $cleanedArray = [];
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    $key = $this->clean($key);
                }

                if (is_array($value)) {
                    $cleanedArray[$key] = $this->cleanArray($value);
                } else {
                    $cleanedArray[$key] = $this->clean($value);
                }
            }

            return $cleanedArray;
        }
    }

    public function clean($html)
    {
        if (is_array($html)) {
            return $this->cleanArray($html);
        }

        $_preserve_replaced_tags = [];
        $html = str_ireplace('{SITE_URL}', '___mw-site-url-temp-replace-on-clean___', $html);

        // from https://portswigger.net/web-security/cross-site-scripting/cheat-sheet#ontransitionend
        $cleanStrings = MwHtmlSanitizerReference::getNotAllowedAttributes();

        $antiXss = new AntiXSS();
        $antiXss->addEvilHtmlTags($cleanStrings);
        $antiXss->addEvilAttributes($cleanStrings);
        $antiXss->addNeverAllowedOnEventsAfterwards($cleanStrings);

        $allowAttibutes = [
            'style',
            'href',
            'alt',
            'target',
            'srcset',
            'sizes',
            'title',
            'xlink:href',
        ];
        $antiXss->removeEvilAttributes($allowAttibutes);

        $allowTags = [
            'head',
            'header',
            'main',
            'aside',
            'img',
            'form',
            'svg',
            'title',
            'input',
            'button',
            'select',
            'option',
            'textarea',
            'picture',
            'source',
        ];

        $antiXss->removeEvilHtmlTags($allowTags);
        $allowRegex = [
            '<!--(.*)-->' => '&lt;!--$1--&gt;',
            '&lt;!--', '&lt;!--$1--&gt;'
        ];

        $antiXss->removeNeverAllowedRegex($allowRegex);

        $allowNotClosed = [
            'li',
            'ul',
            'textarea',
        ];
        $antiXss->removeDoNotCloseHtmlTags($allowNotClosed);

        $html = $antiXss->xss_clean($html);
        $html_to_return = $html;
        if ($_preserve_replaced_tags) {
            foreach ($_preserve_replaced_tags as $key => $value) {
                $html_to_return = str_replace($key, $value, $html_to_return);
            }
        }

        $html_to_return = str_ireplace('___mw-site-url-temp-replace-on-clean___', '{SITE_URL}', $html_to_return);

        return $html_to_return;
    }
}