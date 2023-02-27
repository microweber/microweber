<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class FeedReport extends Component
{
    use WithPagination;

    public $import_feed_id;

    public function render()
    {
        $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();

        $mappedContent = $this->paginate($importFeed->mapped_content);

        return view('import_export_tool::admin.import-wizard.feed-report',compact('importFeed','mappedContent'));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
