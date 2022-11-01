<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\FilterItemComponent;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapCategoryReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;

class FieldMapDropdownItem extends FilterItemComponent
{
    public $name = 'Map to';
    public string $view = 'import_export_tool::admin.field-map-dropdown-item';

}
