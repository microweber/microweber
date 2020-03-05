<?php

namespace Microweber\Utils\Adapters\Template\RenderHelpers;

use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Analytics;


class TemplateOptimizeLoadingHelper
{
    /** @var \Microweber\Application */
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

        $skip = array('gtm-', 'ua-', 'aw-', 'fbevents.js');
        $replaced = array();
        $pq = \phpQuery::newDocument($layout);
        $srcs = array();
        $srcs_css = array();

        foreach ($pq ['link'] as $elem) {
            $type = pq($elem)->attr('type');
            $rel = pq($elem)->attr('rel');

            if ($type == "text/css" or $rel == 'stylesheet') {
                $replaced[] = pq($elem)->htmlOuter();
                pq($elem)->replaceWith('');
            }


        }
        foreach ($pq ['script'] as $elem) {

            $src = pq($elem)->attr('src');

            if ($src) {

            } else {

            }

            $script = pq($elem)->htmlOuter();
            $is_skip = false;
            foreach ($skip as $skip_str) {
                if (stristr($skip_str, $script)) {
                    $is_skip = true;
                }
            }

            if (!$is_skip) {
                $replaced[] = pq($elem)->htmlOuter();
                pq($elem)->replaceWith('');
            }

        }
        $l = $pq->html();
        if ($replaced) {
            $c = 1;
            $inject_before_html_closing_tag_str = implode("\n", $replaced);

            $layout = str_ireplace('</body>', $inject_before_html_closing_tag_str . '</body>', $l, $c);
        }
        return $layout;

    }
}