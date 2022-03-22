<?php

namespace MicroweberPackages\Helper;

class HTMLClean
{
    public $purifierPath;

    public function __constructor(){
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

    public function clean($html) {

        $antiXss = new \voku\helper\AntiXSS();
        $html = $antiXss->xss_clean($html);

        $config = \HTMLPurifier_Config::createDefault();

        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }


        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }
        $config->set('URI.DisableExternal', true);
        $config->set('URI.DisableExternalResources', true);
    //    $config->set('URI.DisableResources', true);
        $config->set('URI.Host', site_hostname());

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
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
