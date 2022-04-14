<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ViewImport extends Component
{
    public $import_feed_id;
    public $import_feed;

    public function save()
    {
        $feed = ImportFeed::where('id', $this->import_feed_id)->first();
        $feed->name = $this->import_feed['name'];
        $feed->download_images = $this->import_feed['download_images'];
        $feed->split_to_parts = $this->import_feed['split_to_parts'];
        $feed->content_tag = $this->import_feed['content_tag'];
        $feed->primary_key = $this->import_feed['primary_key'];
        $feed->update_items = $this->import_feed['update_items'];
        $feed->count_of_contents = $this->import_feed['count_of_contents'];
        $feed->old_content_action = $this->import_feed['old_content_action'];
        $feed->save();
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
        return view('import_export_tool::admin.livewire-view-import');
    }

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();

        $this->import_feed = $importFeed->toArray();
        $this->import_feed_id = $importFeed->id;

        $importFeedNames = [];
        $getImportFeeds = ImportFeed::all();
        if ($getImportFeeds->count() > 0) {
            foreach ($getImportFeeds as $importFeed) {
                $importFeedNames[$importFeed->id] = $importFeed->name;
            }
        }

        $this->import_feed_names = $importFeedNames;
    }
}
