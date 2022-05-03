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

        $html = $antiXss->xss_clean($html);


        return $html;
    }

}
