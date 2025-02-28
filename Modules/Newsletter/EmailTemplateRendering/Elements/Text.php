<?php

namespace Modules\Newsletter\EmailTemplateRendering\Elements;

class Text extends DefaultElement
{
    public function render($params = [])
    {
        $html = '';

        $css = [];
        if (isset($params['data']['style']['fontSize'])) {
            $css[] = 'font-size: ' . $params['data']['style']['fontSize'] . 'px;';
        }
        if (isset($params['data']['style']['fontWeight'])) {
            $css[] = 'font-weight: ' . $params['data']['style']['fontWeight'] . ';';
        }
        if (isset($params['data']['style']['textAlign'])) {
            $css[] = 'text-align: ' . $params['data']['style']['textAlign'] . ';';
        }
        if (isset($params['data']['style']['padding'])) {
            $css[] = 'padding: ' . $params['data']['style']['padding']['top'] . 'px ' . $params['data']['style']['padding']['right'] . 'px ' . $params['data']['style']['padding']['bottom'] . 'px ' . $params['data']['style']['padding']['left'] . 'px;';
        }
        if (isset($params['data']['style']['color'])) {
            $css[] = 'color: ' . $params['data']['style']['color'] . ';';
        }
        if (isset($params['data']['style']['backgroundColor'])) {
            $css[] = 'background-color: ' . $params['data']['style']['backgroundColor'] . ';';
        }

        $html .= '<div style="'.implode(' ', $css).'">';

        if (isset($params['data']['props']['text'])) {
            $html .= $params['data']['props']['text'];
        }

        $html .= '</div>';

        return $html;
    }

}
