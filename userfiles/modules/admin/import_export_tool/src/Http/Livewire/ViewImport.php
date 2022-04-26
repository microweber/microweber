<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ViewImport extends Component
{
    use WithFileUploads;

    public $has_new_changes = 0;
    public $import_feed_id;
    public $import_feed = [];
    public $import_feed_original = [];
    public $confirming_delete_id;
    public $delete_also_content = 0;
    public $photo;

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

        return redirect(route('admin.import-export-tool.import', $this->import_feed_id));
    }

    public function confirmDelete($id)
    {
        $this->confirming_delete_id = $id;
    }

    public function delete($id)
    {
        $id = intval($id);
        $feed = ImportFeed::where('id', $id)->first();

        if ($this->delete_also_content == 1) {
            if (!empty($feed->imported_content_ids)) {
                foreach ($feed->imported_content_ids as $contentId) {
                    Content::where('id', $contentId)->delete();
                }
            }
        }

        $feed->delete();

        return $this->redirect(route('admin.import-export-tool.index'));
    }

    public function download()
    {
        $sourceFile = $this->import_feed['source_file'];

        $feed = ImportFeed::where('id', $this->import_feed_id)->first();

        if ($feed->downloadFeed($sourceFile)) {
            session()->flash('message', 'Feed is downloaded successfully.');
            return redirect(route('admin.import-export-tool.import', $this->import_feed_id));
        }

        return ['downloaded' => false];
    }

    public function upload()
    {

    }

    public function render()
    {
        $this->has_new_changes = $this->arrayDiffRecursive($this->import_feed_original, $this->import_feed);

        return view('import_export_tool::admin.livewire-view-import');
    }

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_feed = $importFeed->toArray();
        unset($this->import_feed['source_content']);

        $this->import_feed_original = $importFeed->toArray();
        unset($this->import_feed_original['source_content']);

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

    public function arrayDiffRecursive($array1, $array2)
    {
        foreach ($array1 as $key => $value) {

            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffRecursive($value, $array2[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif ((!isset($array2[$key]) || $array2[$key] != $value) && !($array2[$key] === null && $value === null)) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }
}
