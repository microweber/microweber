<?php

namespace MicroweberPackages\Security;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerReference;
use voku\helper\AntiXSS;

class XSSClean
{
    /**
     * task-2026-09-23 — the fully-configured AntiXSS instance, built once per
     * request and reused. Previously clean() did `new AntiXSS()` AND re-applied
     * the entire evil-tag/attribute/allow-list config on EVERY call; cleaning a
     * large structure (e.g. the admin content tree: ~7 fields × 1500 nodes ≈ 10k
     * calls) cost ~25s just constructing/configuring AntiXSS. The config never
     * depends on the input, so it is built once here. Static so it is shared
     * across every `new XSSClean()` (the global xss_clean() helper makes a fresh
     * instance per call).
     *
     * @var AntiXSS|null
     */
    protected static ?AntiXSS $antiXss = null;

    protected static function antiXss(): AntiXSS
    {
        if (self::$antiXss !== null) {
            return self::$antiXss;
        }

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

        return self::$antiXss = $antiXss;
    }

    /**
     * @param array<mixed> $array
     * @return array<mixed>
     */
    public function cleanArray(array $array): array
    {
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

    /**
     * @param mixed $html
     * @return mixed
     */
    public function clean(mixed $html): mixed
    {
        if (is_array($html)) {
            return $this->cleanArray($html);
        }

        $html = str_ireplace('{SITE_URL}', '___mw-site-url-temp-replace-on-clean___', $html);

        $html_to_return = self::antiXss()->xss_clean($html);
        $html_to_return = str_ireplace('___mw-site-url-temp-replace-on-clean___', '{SITE_URL}', $html_to_return);

        return $html_to_return;
    }
}