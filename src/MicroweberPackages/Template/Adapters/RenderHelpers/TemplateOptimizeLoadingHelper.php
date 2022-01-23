<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Analytics;


class TemplateOptimizeLoadingHelper
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function render($layout)
    {
        $layout = $this->_move_scripts_to_bottom_of_page($layout);
        return $layout;

    }


    private function _move_scripts_to_bottom_of_page($layout)
    {

        $skip = array('gtm-', 'ua-', 'aw-', 'sdk-', 'fbevents.js','analytics');
        $replaced = array();
        $preload = array();
        $dns_prefetch = array();
        $pq = \phpQuery::newDocument($layout);

        foreach ($pq->find('*') as $elem) {


            $type = pq($elem)->attr('type');
            $rel = pq($elem)->attr('rel');
            $src = pq($elem)->attr('href');

            $tag = $elem->tagName;

            if ($tag == 'link' and ($type == "text/css" or $rel == 'stylesheet')) {
                $replaced[] = pq($elem)->htmlOuter();
                pq($elem)->replaceWith('');
                if ($src) {
                    $preload[] = '<link rel="preload" href="' . $src . '" as="style" />';
                    //$preload[] = '<link rel="preload" onload="this.rel=\'stylesheet\'" href="' . $src . '" as="style" />';
                }
            } elseif ($tag == 'script') {

                $src = pq($elem)->attr('src');
                if ($src) {
                    $preload[] = '<link rel="preload" href="' . $src . '" as="script" />';
                }

                $script = pq($elem)->htmlOuter();
                $is_skip = false;
                foreach ($skip as $skip_str) {
                    if (strpos($script,$skip_str)) {
                        $is_skip = true;
                    }
                }

                if (!$is_skip) {
                    $replaced[] = pq($elem)->htmlOuter();
                    pq($elem)->replaceWith('');
                }
            }
        }


//        foreach ($pq ['link'] as $elem) {
//            $type = pq($elem)->attr('type');
//            $rel = pq($elem)->attr('rel');
//            $src = pq($elem)->attr('href');
//
//
//            if ($tag == 'link' and ($type == "text/css" or $rel == 'stylesheet')) {
//
//                $replace_key = '<!-- replaced-asset -->' . $replace_num++;
//                $replaced[] = pq($elem)->htmlOuter();
//                pq($elem)->replaceWith('');
//            }
//
//            if ($src) {
//                $preload[] = '<link rel="preload" href="' . $src . '" as="style" />';
//            }
//
//        }
//        foreach ($pq ['script'] as $elem) {
//
//            $src = pq($elem)->attr('src');
//            if ($src) {
//                $preload[] = '<link rel="preload" href="' . $src . '" as="script" />';
//            }
//
//            $script = pq($elem)->htmlOuter();
//            $is_skip = false;
//            foreach ($skip as $skip_str) {
//                if (stristr($skip_str, $script)) {
//                    $is_skip = true;
//                }
//            }
//
//            if (!$is_skip) {
//                $replaced[] = pq($elem)->htmlOuter();
//                pq($elem)->replaceWith('');
//            }
//
//        }
        $l = $pq->html();
        if ($replaced) {
            $c = 1;
            $inject = implode("\n", $replaced);
            $layout = str_ireplace('</body>', $inject . '</body>', $l, $c);
        }
        if ($preload) {
            $c = 1;
            $inject = implode("\n", $preload);
            $layout = str_ireplace('</head>', $inject . '</head>', $layout, $c);
        }


        return $layout;

    }
}
