<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HtmlDropdownMappingSimpleTable extends HtmlDropdownMappingRecursiveTable
{
    public function render()
    {

        $contentKeys = [];
        if (isset($this->content[$this->contentParentTags][0])) {
            foreach ($this->content[$this->contentParentTags][0] as $itemKey => $itemValue) {
                $contentKeys[$itemKey] = $itemValue;
            }
        }

        return '';
    }

}
