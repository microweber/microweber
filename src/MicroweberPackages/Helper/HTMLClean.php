<?php

namespace MicroweberPackages\Helper;

class HTMLClean
{
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

        $config->set('HTML.Allowed', 'a[href|title],img[title|src],em,strong,cite,blockquote,code,ul,ol,li,dl,dt,dd,p,br,h1,h2,h3,h4,h5,h6,span,*[style]');

        $config->set('HTML.AllowedElements', ['span', 'p', 'br', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'strong', 'em', 'u', 'ul', 'li', 'ol', 'hr', 'blockquote', 'sub', 'sup', 'img']);
        $config->set('HTML.AllowedAttributes', '*.style,*.target,*.title,*.href,*.class,*.src,*.border,*.width,*.height,*.title,*.name,*.id');

        $config->set('AutoFormat.Linkify', false);
        $config->set('AutoFormat.RemoveEmpty', TRUE);
        $config->set("AutoFormat.RemoveEmpty.Predicate", ['colgroup' => [], 'th' => [], 'td' => [], 'iframe' => ['src'], 'div' => ['class']]);
        $config->set("AutoFormat.RemoveEmpty", true);
        $config->set("Core.NormalizeNewlines", true);
        $config->set("Core.RemoveInvalidImg", true);


        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $config->set('CSS.AllowedProperties', array());
        $config->set('HTML.ForbiddenAttributes', array('*@class','*@alt'));

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }
}
