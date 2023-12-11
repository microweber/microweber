<?php

namespace MicroweberPackages\Helper;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HTMLClean
{
    public $purifierPath;

    public function __construct(){
        $path = storage_path() . '/html_purifier';
        if (!is_dir($path)) {
            mkdir_recursive($path);
        }
        $this->purifierPath = $path;
    }

    public function cleanArray($array) {

        if (is_array($array)) {

            $cleanedArray = [];
            foreach ($array as $key=>$value) {
                if (is_array($value)) {
                    $cleanedArray[$key] = $this->cleanArray($value);
                } else {
                    $cleanedArray[$key] = $this->clean($value);
                }
            }

            return $cleanedArray;
        }
    }

    public function clean($html,$options = []) {



        $xssClean = new XSSClean();
        $html = $xssClean->clean($html);

        $config = (new HtmlSanitizerConfig())
            // Allow "safe" elements and attributes. All scripts will be removed
            // as well as other dangerous behaviors like CSS injection
            ->allowSafeElements()

            // Allow all static elements and attributes from the W3C Sanitizer API
            // standard. All scripts will be removed but the output may still contain
            // other dangerous behaviors like CSS injection (click-jacking), CSS
            // expressions, ...
            ->allowStaticElements()

            // Allow the "div" element and no attribute can be on it
            ->allowElement('div')

            // Allow the "a" element, and the "title" attribute to be on it
         //   ->allowElement('a', ['title'])

            // Allow the "span" element, and any attribute from the Sanitizer API is allowed
            // (see https://wicg.github.io/sanitizer-api/#default-configuration)
            ->allowElement('span', '*')
            ->allowElement('module', '*')
            ->allowElement('div', '*')

            // Block the "section" element: this element will be removed but
            // its children will be retained
           // ->blockElement('section')

            // Drop the "div" element: this element will be removed, including its children
          //  ->dropElement('div')

            // Allow the attribute "title" on the "div" element
         //   ->allowAttribute('title', ['div'])

            // Allow the attribute "data-custom-attr" on all currently allowed elements
            ->allowAttribute('*', '*')

            // Drop the "data-custom-attr" attribute from the "div" element:
            // this attribute will be removed
          //  ->dropAttribute('data-custom-attr', ['div'])

            // Drop the "data-custom-attr" attribute from all elements:
            // this attribute will be removed
          //  ->dropAttribute('data-custom-attr', '*')

            // Forcefully set the value of all "rel" attributes on "a"
            // elements to "noopener noreferrer"
            ->forceAttribute('a', 'rel', 'noopener noreferrer')

            // Transform all HTTP schemes to HTTPS
           // ->forceHttpsUrls()

            // Configure which schemes are allowed in links (others will be dropped)
            ->allowLinkSchemes(['https', 'http', 'mailto'])

            // Configure which hosts are allowed in links (by default all are allowed)
          //  ->allowLinkHosts(['symfony.com', 'example.com'])

            // Allow relative URL in links (by default they are dropped)
            ->allowRelativeLinks()

            // Configure which schemes are allowed in img/audio/video/iframe (others will be dropped)
            ->allowMediaSchemes(['https', 'http'])

            // Configure which hosts are allowed in img/audio/video/iframe (by default all are allowed)
          //  ->allowMediaHosts(['symfony.com', 'example.com'])

            // Allow relative URL in img/audio/video/iframe (by default they are dropped)
            ->allowRelativeMedias()

            // Configure a custom attribute sanitizer to apply custom sanitization logic
            // ($attributeSanitizer instance of AttributeSanitizerInterface)
           // ->withAttributeSanitizer($attributeSanitizer)

            // Unregister a previously registered attribute sanitizer
            // ($attributeSanitizer instance of AttributeSanitizerInterface)
         //   ->withoutAttributeSanitizer($attributeSanitizer)
        ;

        $sanitizer = new HtmlSanitizer($config);
        $userInput = $html;

        $html2 = $sanitizer->sanitize($userInput);


        return $html2;
    }

    public function onlyTags($html, $tags = ['i','a','strong','code','pre','blockquote','em','strike','p','span','caption','cite']) {

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
