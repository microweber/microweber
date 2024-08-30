<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class NoExportFeeds extends AdminComponent
{
    public function render()
    {
        return view('import_export_tool::admin.livewire-no-export-feeds');
    }
}
