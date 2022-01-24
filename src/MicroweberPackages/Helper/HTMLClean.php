<?php

namespace MicroweberPackages\Helper;

class HTMLClean
{
    public function clean($html) {

        $antiXss = new \voku\helper\AntiXSS();
        $html = $antiXss->xss_clean($html);


        $path = storage_path() . '/html_purifier';
        $config = \HTMLPurifier_Config::createDefault();
        if ($path) {
            $config->set('Cache.SerializerPath', $path);
        }

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }
}
