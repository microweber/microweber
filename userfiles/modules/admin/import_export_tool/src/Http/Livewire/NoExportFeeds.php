<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class NoExportFeeds extends Component
{
    public function render()
    {
        return view('import_export_tool::admin.livewire-no-export-feeds');
    }
}
