<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class DropdownMapping extends Component
{
    public $mapKey;
    public $dropdowns = [];
    public $selectField = false;
    public $mediaUrlsSeperator = false;
    public $categorySeparator = false;
    public $tagsSeperator = false;

    public function render()
    {
        return view('import_export_tool::admin.dropdown-mapping.dropdown');
    }
}
