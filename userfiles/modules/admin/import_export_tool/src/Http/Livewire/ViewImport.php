<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ViewImport extends Component
{
    public $import_feed_id;
    public $import_feed = [];
    public $confirming_delete_id;

    public function save()
    {
        $feed = ImportFeed::where('id', $this->import_feed_id)->first();
        $feed->source_type = $this->import_feed['source_type'];
        $feed->name = $this->import_feed['name'];
        $feed->download_images = $this->import_feed['download_images'];
        $feed->split_to_parts = $this->import_feed['split_to_parts'];
        $feed->content_tag = $this->import_feed['content_tag'];
        $feed->primary_key = $this->import_feed['primary_key'];
        $feed->update_items = $this->import_feed['update_items'];
        $feed->count_of_contents = $this->import_feed['count_of_contents'];
        $feed->old_content_action = $this->import_feed['old_content_action'];
        $feed->category_separator = $this->import_feed['category_separator'];
        $feed->save();

        session()->flash('message', 'Import feed is saved successfully.');
    }

    public function confirmDelete($id)
    {
        $this->confirming_delete_id = $id;
    }

    public function delete($id)
    {
        $id = intval($id);

        ImportFeed::where('id', $id)->delete();

        return $this->redirect(route('admin.import-export-tool.index'));
    }

    public function download()
    {
        $dir = storage_path() . DS . 'import_export_tool';
        $filename = $dir . DS . md5($this->import_feed['source_file']) .'.txt';
        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }

        $downloaded = mw()->http->url($this->import_feed['source_file'])->download($filename);
        if ($downloaded && is_file($filename)) {

            $newReader = new XmlToArray();
            $xmlArray = $newReader->readXml(file_get_contents($filename));
            if (empty($xmlArray)) {
                unlink($filename);
                return;
            }

            $realpath = str_replace(base_path(),'', $filename);

            $repeatableTargetKeys = $newReader->getArrayRepeatableTargetKeys($xmlArray);
            $repeatableTargetKeys = Arr::dot($repeatableTargetKeys);
            $repeatableData = Arr::get($xmlArray, $this->import_feed['content_tag']);

            $feedUpdate = ImportFeed::where('id', $this->import_feed_id)->first();
            $feedUpdate->source_file = $this->import_feed['source_file'];
            $feedUpdate->source_file_realpath = $realpath;
            $feedUpdate->detected_content_tags = $repeatableTargetKeys;
            $feedUpdate->count_of_contents = count($repeatableData);
            $feedUpdate->mapped_content = [];
            $feedUpdate->source_file_size = filesize($filename);
            $feedUpdate->last_downloaded_date = Carbon::now();
            $feedUpdate->save();

            $this->import_feed = $feedUpdate->toArray();

            session()->flash('message', 'Feed is downloaded successfully.');

            return redirect(route('admin.import-export-tool.import', $this->import_feed_id));

            return ['downloaded'=>true];
        }

        return ['downloaded'=>false];
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
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

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
