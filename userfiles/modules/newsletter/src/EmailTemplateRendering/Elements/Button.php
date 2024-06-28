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
        if (isset($params['data']['style']['backgroundColor'])) {
            $css[] = 'background-color: ' . $params['data']['style']['backgroundColor'] . ';';
        }
        if (isset($params['data']['style']['fontWeight'])) {
            $css[] = 'font-weight: ' . $params['data']['style']['fontWeight'] . ';';
        }

        $buttonDefaultStyle = [
            'color' => '#FFFFFF',
            'font-weight' => 'bold',
            'background-color' => '#999999',
            'display' => 'inline-block',
            'padding' => '12px 20px',
            'text-decoration' => 'none',
        ];
        if (isset($params['data']['props']['buttonBackgroundColor'])) {
            $buttonDefaultStyle['background-color'] = $params['data']['props']['buttonBackgroundColor'];
        }
        if (isset($params['data']['props']['buttonTextColor'])) {
            $buttonDefaultStyle['color'] = $params['data']['props']['buttonTextColor'];
        }
        if (isset($params['data']['props']['size'])) {
            if ($params['data']['props']['size'] == 'small') {
                $buttonDefaultStyle['padding'] = '10px 15px';
            }
            if ($params['data']['props']['size'] == 'large') {
                $buttonDefaultStyle['padding'] = '15px 25px';
            }
        }

        $cssButton = [];
        $cssButton[] = 'color: ' . $buttonDefaultStyle['color'] . ';';
        $cssButton[] = 'font-weight: ' . $buttonDefaultStyle['font-weight'] . ';';
        $cssButton[] = 'background-color: ' . $buttonDefaultStyle['background-color'] . ';';
        $cssButton[] = 'display: ' . $buttonDefaultStyle['display'] . ';';
        $cssButton[] = 'padding: ' . $buttonDefaultStyle['padding'] . ';';
        $cssButton[] = 'text-decoration: ' . $buttonDefaultStyle['text-decoration'] . ';';


        $html .= '<div style="'.implode(' ', $css).'">';
            $html .= '<a style="'.implode(' ', $cssButton).'" href="'.$params['data']['props']['url'].'">';
            $html .= $params['data']['props']['text'];
            $html .= '</a>';
        $html .= '</div>';

        return $html;
    }
}
