<?php

namespace Modules\Newsletter\EmailTemplateRendering\Elements;

class Heading extends DefaultElement
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

        $html .= '<div style="'.implode(' ', $css).'">';

        if (!isset($params['data']['props']['level'])) {
            $params['data']['props']['level'] = 'h1';
        }

        if (isset($params['data']['props']['text'])) {
            $html .= '<'.$params['data']['props']['level'].'>'.$params['data']['props']['text'].'</'.$params['data']['props']['level'].'>';
        }

        $html .= '</div>';

        return $html;
    }
}
