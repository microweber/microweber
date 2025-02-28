<?php

namespace Modules\Newsletter\EmailTemplateRendering\Elements;

class Spacer extends DefaultElement
{

    public function render($params = [])
    {
        $html = '';

        $css = [];
        if (isset($params['data']['props']['height'])) {
            $css[] = 'height: ' . $params['data']['props']['height'] . 'px;';
        }

        $html .= '<div style="'.implode(' ', $css).'"></div>';

        return $html;
    }

}
