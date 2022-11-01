<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HtmlDropdownMappingSimpleTable extends HtmlDropdownMappingRecursiveTable
{
    public function render()
    {
        $html = '<table class="table">';

        $contentKeys = [];
        if (isset($this->content[$this->contentParentTags][0])) {
            foreach ($this->content[$this->contentParentTags][0] as $itemKey => $itemValue) {
                $contentKeys[$itemKey] = $itemValue;
            }
        }

        $html .= '<tbody>';

        if (!empty($contentKeys)) {
            foreach ($contentKeys as $itemKey => $itemValue) {
                $html .= '<tr>';
                $html .= '<td>' . $itemKey . '</td>';
                $html .= "<td> dd </td>";
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';

        $html = "@livewire('admin-filter-item-users', [])";

        return $html;
    }

}
