<?php

namespace Modules\Newsletter\EmailTemplateRendering\Elements;

class EmailLayout extends DefaultElement
{
    public function render($params = [])
    {

        $css = [];
        if (isset($params['data']['borderRadius'])) {
            $css[] = 'border-radius: ' . $params['data']['borderRadius'] . 'px;';
        }
        if (isset($params['data']['canvasColor'])) {
            $css[] = 'background-color: ' . $params['data']['canvasColor'] . ';';
        }
        if (isset($params['data']['textColor'])) {
            $css[] = 'color: ' . $params['data']['textColor'] . ';';
        }
        if (isset($params['data']['fontFamily'])) {
            $css[] = 'font-family:Bahnschrift, &quot;DIN Alternate&quot;, &quot;Franklin Gothic Medium&quot;, &quot;Nimbus Sans Narrow&quot;, sans-serif-condensed, sans-serif;';
        }
        $cssBody = [];
        if (isset($params['data']['backdropColor'])) {
            $cssBody[] = 'background-color: ' . $params['data']['backdropColor'] . ';';
        }

        $html = '';
        $html .= '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<title>Document</title>';
        $html .= '</head>';

        $html .= '<body style="'.implode(' ', $cssBody).'">';
        $html .= '<style>';
        $html .= 'a { color: #000; text-decoration: none; };';
        $html .= '</style>';

        $html .= '<div style="margin:0 auto;max-width:600px;'.implode(' ', $css).'">';

        if (isset($params['data']['childrenIds'])) {
            foreach ($params['data']['childrenIds'] as $childrenId) {
                $html .= $this->renderChildren($childrenId);
            }
        }

        $html .= '</div>';

        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }
}
