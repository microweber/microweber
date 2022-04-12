<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ViewImport extends Component
{
    public $import_feed_id = 1;
    public $import_feed;

    public function save()
    {

    }

    public function download()
    {
        $feed = ImportFeed::where('id', $this->import_feed_id)->first();
        $feed->source_file = $this->import_feed['source_file'];
        $feed->source_file_size = "5MB";
        $feed->last_downloaded_date = Carbon::now();
        $feed->save();
    }

    public function upload()
    {

    }

    public function startImporting()
    {

    }

    public function render()
    {
        $this->import_feed = ImportFeed::where('id', $this->import_feed_id)->first()->toArray();

        return view('import_export_tool::admin.livewire-view-import');
    }

}
