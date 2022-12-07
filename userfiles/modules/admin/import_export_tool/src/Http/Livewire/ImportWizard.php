<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportWizard extends Component
{
    protected $queryString = ['tab', 'importTo','importFeedId'];

    public $tab = 'type';
    public $importTo = 'type';
    public $importFeedId;

    public $import_feed = [];
    public $import_feed_edit_name = 0;

    public $listeners = [
        'uploadFeedReadFile'=>'readUploadedFile',
        'refreshImportFeedStateById'=> 'refreshImportFeedStateById',
        'readFeedFile'=>'readFeedFile',
        'importingFinished'=>'importingFinished',
    ];

    public function editName()
    {
        $this->import_feed_edit_name = 1;
    }

    public function closeEditName()
    {
        $this->import_feed_edit_name = 0;

        $findImportFeed = ImportFeed::where('id', $this->import_feed['id'])->first();
        if ($findImportFeed) {
            $findImportFeed->name = $this->import_feed['name'];
            $findImportFeed->save();
        }

    }

    public function showTab($tab)
    {
        $this->tab = $tab;

        if ($tab == 'upload') {
            if ($this->import_feed['source_type'] == 'upload_file') {
                $this->emit('initJsUploader');
            }
        }
    }

    public function selectImportTo($importTo)
    {
        $this->showTab('upload');
        $this->importTo = $importTo;

        $findImportFeed = ImportFeed::where('id', $this->import_feed['id'])->first();
        if (!$findImportFeed) {
            $findImportFeed = new ImportFeed();
            $findImportFeed->is_draft = 1;
        }

        $findImportFeed->resetFeed();
        $findImportFeed->name = 'Import ' . ucfirst($importTo);
        $findImportFeed->import_to = $importTo;
        $findImportFeed->save();

        // refresh state
        $this->refreshImportFeedState($findImportFeed->toArray());
    }

    public function download()
    {
        $sourceUrl = $this->import_feed['source_url'];

        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();

        if ($feed->downloadFeed($sourceUrl)) {
            session()->flash('successMessage', 'Feed is downloaded successfully.');
            $this->dispatchBrowserEvent('read-feed-from-file');
        } else {
            session()->flash('errorMessage', 'Feed can\'t be downloaded.');
        }

        $this->refreshImportFeedState($feed->toArray());
    }

    public function changeContentTag()
    {
        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();
        if ($feed) {

            if ($feed->content_tag != $this->import_feed['content_tag']) {
                $feed->content_tag = $this->import_feed['content_tag'];
                $feed->save();
            }

            $this->readFeedFile();

            $feed = ImportFeed::where('id', $this->import_feed['id'])->first();
            $this->refreshImportFeedState($feed->toArray());

            $this->emit('dropdownMappingPreviewRefresh');

        }
    }

    public function readFeedFile()
    {
        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();
        if ($feed) {
            $feedFile = $feed->source_file_realpath;

            if (is_file($feedFile)) {

                $fileExt = pathinfo($feedFile, PATHINFO_EXTENSION);
                $read = $feed->readContentFromFile($feedFile, $fileExt);

                if ($read) {
                    $this->showTab('map');
                    session()->flash('successMessage', 'Feed is read successfully.');

                    // Read new feed
                    $feed = ImportFeed::where('id',$this->import_feed['id'])->first();
                    $this->refreshImportFeedState($feed->toArray());

                } else {
                    session()->flash('errorMessage', 'No content found in feed file.');
                }
            } else {
                session()->flash('errorMessage', 'Can\'t read feed.');
            }
        }
    }

    public function readUploadedFile($filename)
    {
        if (empty(trim($filename))) {
            return;
        }

        $fullFilePath = ImportFeed::getImportTempPath() . 'uploaded_files'.DS.$filename;
        if (!is_file($fullFilePath)) {
            return;
        }

        $uploadFilePath = str_replace(storage_path(), '', $fullFilePath);

        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();

        if($fullFilePath != $feed->source_file_realpath) {
            $feed->resetFeed();
        }

        $feed->source_type = 'upload_file';
        $feed->source_file = $uploadFilePath;
        $feed->source_file_realpath = $fullFilePath;
        $feed->last_downloaded_date = Carbon::now();
        $feed->save();

        $this->dispatchBrowserEvent('read-feed-from-file');
        session()->flash('successMessage', 'Feed is uploaded successfully.');

    }

    public function saveMapping()
    {
        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();

        if ($feed) {

            $feedMapToArray = new FeedMapToArray();
            $feedMapToArray->setImportFeedId($feed->id);
            $preparedData = $feedMapToArray->toArray();

            $feed->saveMappedContent($preparedData);
            $feed->save();

            $this->refreshImportFeedState($feed->toArray());
            $this->showTab('import');
        }
    }

    public function importingFinished()
    {
        $this->showTab('report');
    }

    public function updatedImportFeed()
    {
        $feed = ImportFeed::where('id', $this->import_feed['id'])->first();

        if ($feed) {

            if ($this->import_feed['source_type'] == 'upload_file') {
                $this->emit('initJsUploader');
            }

            $feed->primary_key = $this->import_feed['primary_key'];
            $feed->download_images = $this->import_feed['download_images'];
            $feed->split_to_parts = $this->import_feed['split_to_parts'];
            $feed->parent_page = $this->import_feed['parent_page'];
            $feed->save();
        }
    }

    public function mount()
    {
        $importFeedId = request()->get('importFeedId');
        $findImportFeed = false;
        if ($importFeedId) {
            $findImportFeed = ImportFeed::where('id', $importFeedId)->first();
        }

        if (!$findImportFeed) {
            $newDraftFeed = new ImportFeed();
            $newDraftFeed->is_draft = 1;
            $newDraftFeed->save();
            $findImportFeed = ImportFeed::where('id', $newDraftFeed->id)->first();
        }

        if ($findImportFeed) {
            $this->refreshImportFeedState($findImportFeed->toArray());
        }
    }

    public function refreshImportFeedStateById($id)
    {
        $findImportFeed = ImportFeed::where('id', $id)->first();
        if ($findImportFeed) {
            $this->refreshImportFeedState($findImportFeed->toArray());
        }
    }

    public function refreshImportFeedState($importFeed)
    {
        $this->import_feed = $importFeed;

        $this->import_feed['mapped_content_count'] = 0;
        if (is_array($this->import_feed['mapped_content'])) {
            $this->import_feed['mapped_content_count'] = count($this->import_feed['mapped_content']);
        }

        $this->import_feed['source_content_count'] = 0;
        if (is_array($this->import_feed['source_content'])) {
            $this->import_feed['source_content_count'] = count($this->import_feed['source_content']);
        }

        $this->importFeedId = $this->import_feed['id'];

        unset($this->import_feed['mapped_content']);
        unset($this->import_feed['source_content']);
    }

    public function render()
    {
        return view('import_export_tool::admin.import-wizard.index');
    }
}
