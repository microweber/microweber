<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ListImports extends Component
{
    public function render()
    {
        $importFeeds = ImportFeed::get();

        return view('import_export_tool::admin.livewire.list-imports', ['import_feeds' => $importFeeds])->extends('admin::layouts.app');
    }

}
