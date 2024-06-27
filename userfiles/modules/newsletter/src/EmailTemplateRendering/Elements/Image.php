<?php

namespace MicroweberPackages\Modules\Newsletter\EmailTemplateRendering\Elements;

class Image
{
    public function render($params = [])
    {
        $html = '';

        $css = [];
        if (isset($params['data']['style']['padding'])) {
            $css[] = 'padding: ' . $params['data']['style']['padding']['top'] . 'px ' . $params['data']['style']['padding']['right'] . 'px ' . $params['data']['style']['padding']['bottom'] . 'px ' . $params['data']['style']['padding']['left'] . 'px;';
        }
        if (isset($params['data']['style']['textAlign'])) {
            $css[] = 'text-align: ' . $params['data']['style']['textAlign'] . ';';
        }

        $attributres = '';
        if (isset($params['data']['props']['width'])) {
            $attributres .= ' width="'.$params['data']['props']['width'].'"';
        }
        if (isset($params['data']['props']['url'])) {
            $attributres .= ' src="'.$params['data']['props']['url'].'"';
        }
        if (isset($params['data']['props']['alt'])) {
            $attributres .= ' alt="'.$params['data']['props']['alt'].'"';
        }
        if (isset($params['data']['props']['contentAlignment'])) {
            $attributres .= ' align="'.$params['data']['props']['contentAlignment'].'"';
        }

        $attributres .= ' style="max-width: 100%; height: auto;"';

        $html .= '<div style="'.implode(' ', $css).'">';

        if (isset($params['data']['props']['linkHref'])) {
            $html .= '<a href="'.$params['data']['props']['linkHref'].'">';
        }

        $html .= '<img '.$attributres.' />';

        if (isset($params['data']['props']['linkHref'])) {
            $html .= '</a>';
        }

        $html .= '</div>';


        return $html;
    }

}
