<?php

namespace MicroweberPackages\Helper;

class HTMLClean
{
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

        $path = storage_path() . '/html_purifier';
        if (!is_dir($path)) {
            mkdir_recursive($path);
        }

        $config = \HTMLPurifier_Config::createDefault();
        if ($path) {
            $config->set('Cache.SerializerPath', $path);
        }

        $config->set('URI.DisableExternal', true);
        $config->set('URI.DisableExternalResources', true);
    //    $config->set('URI.DisableResources', true);
        $config->set('URI.Host', site_hostname());

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }
}
