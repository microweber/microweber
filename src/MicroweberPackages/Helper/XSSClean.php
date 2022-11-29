<?php

namespace MicroweberPackages\Helper;

use voku\helper\AntiXSS;

class XSSClean
{


    public function cleanArray($array)    {

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
        if(is_array($html)){
            return $this->cleanArray($html);
        }


        $_preserve_replaced_tags = [];
        $html = str_ireplace('{SITE_URL}','___mw-site-url-temp-replace-on-clean___', $html);
//        $tags = [ 'textarea', 'pre','code', 'svg', 'kbd'];
//
//        foreach ($tags as $tag) {
//
//            //  $script_pattern = "/<".$tag."[^>]*>(.*)<\/.$tag.>/Uis";
//            $script_pattern = "/\<" . $tag . "(.*?)?\>(.|\s)*?\<\/" . $tag . "\>/i";
//
//            preg_match_all($script_pattern, $html, $mw_script_matches);
//
//            if (!empty($mw_script_matches)) {
//                foreach ($mw_script_matches [0] as $key => $value) {
//                    if ($value != '') {
//                        $v1 = crc32($value);
//                        $v1 = 'mw_xss_clean_repeserve_tags_tag_' . $tag . $v1 . '';
//                        $html = str_replace($value, $v1, $html);
//                        $_preserve_replaced_tags[$v1] = $value;
//
//                    }
//                }
//            }
//
//        }










         // from https://portswigger.net/web-security/cross-site-scripting/cheat-sheet#ontransitionend
        $cleanStrings = [
            'ontransitionstart',
            'onwebkitanimationend',
            'onwebkitanimationiteration',
            'onwebkitanimationstart',
            'onwebkittransitionend',
            'ontransitionrun',
            'onloadedmetadata',
            'ondurationchange',
            'oncanplaythrough',
            'oncuechange',
            'onbounce',
            'onbegin',
            'onbeforeunload',
            'onbeforescriptexecute',
            'onbeforeprint',
            'onanimationstart',
            'onanimationiteration',
            'onanimationend',
            'onanimationcancel',
            'onafterscriptexecute',
            'onfocusin',
            'onhashchange',
            'onload',
            'onunload',
            'onloadend',
            'onloadstart',
            'onmessage',
            'onpageshow',
            'onloadedmetadata',
            'onloadeddata',
            'onplay',
            'onplaying',
            'onpopstate',
            'onprogress',
            'onrepeat',
            'onresize',
            'onscroll',
            'onstart',
            'ontimeupdate',
            'ontoggle',
            'ontransitionend',
            'ontransitioncancel',
            'ontransitionrun',
            'ontransitionstart',
            'onafterprint',
            'onauxclick',
            'onbeforecopy',
            'onbeforecut',
            'onblur',
            'onchange',
            'onclick',
            'onclose',
            'oncontextmenu',
            'oncopy',
            'oncut',
            'ondblclick',
            'ondrag',
            'ondragend',
            'ondragenter',
            'ondragleave',
            'ondragover',
            'ondragstart',
            'ondragexit',
            'ondrop',
            'onfocusout',
            'onfullscreenchange',
            'oninput',
            'oninvalid',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onmousedown',
            'onmouseenter',
            'onmouseleave',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onmozfullscreenchange',
            'onpagehide',
            'onpaste',
            'onpause',
            'onpointerdown',
            'onpointerenter',
            'onpointerleave',
            'onpointermove',
            'onpointerout',
            'onpointerover',
            'onpointerrawupdate',
            'onpointerup',
            'onreset',
            'onsearch',
            'onseeked',
            'onseeking',
            'onselect',
            'onselectionchange',
            'onselectstart',
            'onshow',
            'onsubmit',
            'ontouchend',
            'ontouchmove',
            'ontouchstart',
            'onvolumechange',
            'onwheel',
            'onWebkitAnimationEnd',
            'onWebkitAnimationIteration',
            'onWebkitAnimationStart',
            'onWebkitTransitionEnd',
            'onwebkitTransitionEnd',
            'onunhandledrejection'
        ];

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
//            '<!--(.*)-->' => '<!--(.*)-->',
//            '&lt;!--',
//            '&lt;!--$1--&gt;'
            '<!--(.*)-->' => '&lt;!--$1--&gt;',
            '&lt;!--', '&lt;!--$1--&gt;'
        ];



        $antiXss->removeNeverAllowedRegex($allowRegex);

        $allowNotClosed= [
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


        $html_to_return = str_ireplace('___mw-site-url-temp-replace-on-clean___','{SITE_URL}', $html_to_return);




        return $html_to_return;
    }

}
