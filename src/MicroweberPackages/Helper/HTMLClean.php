<?php

namespace MicroweberPackages\Helper;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerConfig;
use MicroweberPackages\Security\HtmlSanitizer\MwAttrbuteSanitizer;
use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;


class HTMLClean
{
    public $purifierPath;

    public function __construct()
    {
        $path = storage_path() . '/html_purifier';
        if (!is_dir($path)) {
            mkdir_recursive($path);
        }
        $this->purifierPath = $path;
    }

    public function cleanArray($array)
    {

        if (is_array($array)) {

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
    }

    public function clean($html, $options = [])
    {


       $xssClean = new XSSClean();
       $html = $xssClean->clean($html);
        $attributeSanitizer = new MwAttrbuteSanitizer();

       if(isset($options['admin_mode']) and $options['admin_mode']) {
           $config = (new MwHtmlSanitizerConfig())
               ->allowSafeElements()
               ->withMaxInputLength(200000)
               ->allowStaticElements()
               ->allowLinkSchemes(['https', 'http', 'mailto'])
               ->allowRelativeLinks()
               ->allowMediaSchemes(['https', 'http'])
               ->allowRelativeMedias()
               ->withAttributeSanitizer($attributeSanitizer)

           ;
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
               ->withAttributeSanitizer($attributeSanitizer)

           ;
           $sanitizer = new HtmlSanitizer($config);

       }


        $userInput = $html;

        $html = $sanitizer->sanitize($userInput);


         return $html;
    }


    public function onlyTags($html, $tags = ['i', 'a', 'strong', 'code', 'pre', 'blockquote', 'em', 'strike', 'p', 'span', 'caption', 'cite'])
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
