<?php

namespace MicroweberPackages\Security;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerConfig;
use MicroweberPackages\Security\HtmlSanitizer\MwAttrbuteSanitizer;
use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlClean
{
    public string $purifierPath;

    public function __construct(?string $purifierPath = null)
    {
        if ($purifierPath === null) {
            try {
                if (function_exists('storage_path')) {
                    $purifierPath = storage_path() . '/html_purifier';
                }
            } catch (\Throwable $e) {
                // storage_path() requires a booted Laravel app
            }
        }
        if ($purifierPath === null) {
            $purifierPath = sys_get_temp_dir() . '/html_purifier';
        }

        if (!is_dir($purifierPath)) {
            if (function_exists('mkdir_recursive')) {
                mkdir_recursive($purifierPath);
            } else {
                @mkdir($purifierPath, 0755, true);
            }
        }
        $this->purifierPath = $purifierPath;
    }

    /**
     * @param array<mixed> $array
     * @return array<mixed>
     */
    public function cleanArray(array $array): array
    {
        $cleanedArray = [];
        foreach ($array as $key => $value) {
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
     * @param array<string, mixed> $options
     */
    public function clean(mixed $html, array $options = []): mixed
    {
        $xssClean = new XSSClean();
        $html = $xssClean->clean($html);
        $attributeSanitizer = new MwAttrbuteSanitizer();

        if (isset($options['admin_mode']) and $options['admin_mode']) {
            $config = (new MwHtmlSanitizerConfig())
                ->allowSafeElements()
                ->withMaxInputLength(200000)
                ->allowStaticElements()
                ->allowLinkSchemes(['https', 'http', 'mailto'])
                ->allowRelativeLinks()
                ->allowMediaSchemes(['https', 'http'])
                ->allowRelativeMedias()
                ->withAttributeSanitizer($attributeSanitizer);
            $sanitizer = new MwHtmlSanitizer($config);
        } else {
            $config = (new HtmlSanitizerConfig())
                ->allowSafeElements()
                ->withMaxInputLength(200000)
                ->allowStaticElements()
                ->allowLinkSchemes(['https', 'http', 'mailto'])
                ->allowRelativeLinks()
                ->allowMediaSchemes(['https', 'http'])
                ->allowRelativeMedias()
                ->withAttributeSanitizer($attributeSanitizer);
            $sanitizer = new HtmlSanitizer($config);
        }

        $userInput = $html;
        $html = $sanitizer->sanitize($userInput);

        return $html;
    }

    /**
     * @param list<string> $tags
     */
    public function onlyTags(string $html, array $tags = ['i', 'a', 'strong', 'code', 'pre', 'blockquote', 'em', 'strike', 'p', 'span', 'caption', 'cite']): string
    {
        $config = \HTMLPurifier_Config::createDefault();

        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }

        $config->set('HTML.AllowedElements', $tags);
        $config->set('URI.Host', '*');
        $config->set('URI.DisableExternal', false);
        $config->set('URI.DisableExternalResources', false);
        $config->set('HTML.Allowed', 'p,b,a[href],i');
        $config->set('HTML.AllowedAttributes', 'a.href');

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }
}