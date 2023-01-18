<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ListImports extends Component
{
    public function deleteImport($id)
    {
        $findImportFeed = ImportFeed::where('id', $id)->first();
        if ($findImportFeed) {
            $findImportFeed->delete();
        }

        return redirect(route('admin.import-export-tool.index'));
    }

    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {

        return view('livewire.counter');


        $importFeeds = ImportFeed::get();

        return view('import_export_tool::admin.livewire.list-imports', ['import_feeds' => $importFeeds]);
    }

}
