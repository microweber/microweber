<?php

namespace MicroweberPackages\Modules\Newsletter\EmailTemplateRendering\Elements;

class ColumnsContainer extends DefaultElement
{

    public function render($params = [])
    {
        $html = '';

        $css = [];
        if (isset($params['data']['style']['padding'])) {
            $css[] = 'padding: ' . $params['data']['style']['padding']['top'] . 'px ' . $params['data']['style']['padding']['right'] . 'px ' . $params['data']['style']['padding']['bottom'] . 'px ' . $params['data']['style']['padding']['left'] . 'px;';
        }

        $html .= '<div style="'.implode(' ', $css).'">';

        $columnsCount = $params['data']['props']['columnsCount'];
        $columnsGap = $params['data']['props']['columnsGap'];
        $columns = $params['data']['props']['columns'];

        $html .= '<div style="display: flex; gap:'.$columnsGap.'px ">';

        if (isset($params['data']['props']['columns'])) {
            foreach ($columns as $column) {
                if (isset($column['childrenIds'])) {
                    if (empty($column['childrenIds'])) {
                        continue;
                    }
                    foreach ($column['childrenIds'] as $childrenId) {
                        $html .= $this->renderChildren($childrenId);
                    }
                }
            }
        }

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

}
