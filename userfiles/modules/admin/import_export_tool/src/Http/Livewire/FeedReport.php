<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class FeedReport extends Component
{
    public $import_feed_id;

    public function render()
    {
        $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();

        return view('import_export_tool::admin.import-wizard.feed-report',compact('importFeed'));
    }
}
