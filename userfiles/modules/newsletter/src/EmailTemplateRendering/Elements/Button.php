<?php

namespace MicroweberPackages\Modules\Newsletter\EmailTemplateRendering\Elements;

class Button extends DefaultElement
{

    public function render($params = [])
    {
        $html = '';

        $css = [];
        if (isset($params['data']['style']['fontSize'])) {
            $css[] = 'font-size: ' . $params['data']['style']['fontSize'] . 'px;';
        }
        if (isset($params['data']['style']['textAlign'])) {
            $css[] = 'text-align: ' . $params['data']['style']['textAlign'] . ';';
        }
        if (isset($params['data']['style']['padding'])) {
            $css[] = 'padding: ' . $params['data']['style']['padding']['top'] . 'px ' . $params['data']['style']['padding']['right'] . 'px ' . $params['data']['style']['padding']['bottom'] . 'px ' . $params['data']['style']['padding']['left'] . 'px;';
        }

        $cssButton = [];
        $cssButton[] = 'color: #FFFFFF;';
        $cssButton[] = 'font-weight: bold;';
        $cssButton[] = 'background-color: #999999;';
        $cssButton[] = 'display: inline-block;';
        $cssButton[] = 'padding: 12px 20px;';
        $cssButton[] = 'text-decoration: none;';

        if (isset($params['data']['props']['buttonStyle'])) {
            $cssButton[] = 'border-radius: 50px;';
        }
        if (isset($params['data']['props']['fullWidth']) && $params['data']['props']['fullWidth']) {
            $cssButton[] = 'width: 100%;';
        }
        if (isset($params['data']['props']['size'])) {
            $cssButton[] = 'font-size: ' . $params['data']['props']['size'] . 'px;';
        }

        $html .= '<div style="'.implode(' ', $css).'">';
            $html .= '<a style="'.implode(' ', $cssButton).'" href="'.$params['data']['props']['url'].'">';
            $html .= $params['data']['props']['text'];
            $html .= '</a>';
        $html .= '</div>';

        return $html;
    }
}
