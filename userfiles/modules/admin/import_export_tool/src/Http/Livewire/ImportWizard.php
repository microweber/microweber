<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportWizard extends Component
{
    use WithFileUploads;

    protected $queryString = ['tab', 'importTo','importFeedId'];

    public $tab = 'type';
    public $importTo = 'type';
    public $importFeedId;

    public $upload_file;
    public $import_feed = [];

    public $listeners = [
        'readFeedFile'=>'readFeedFile'
    ];

    public function showTab($tab)
    {
        $this->tab = $tab;
    }

    public function selectImportTo($importTo)
    {
        $this->showTab('upload');
        $this->importTo = $importTo;

        $findImportFeed = ImportFeed::where('is_draft', 1)->first();
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

        $feed = ImportFeed::where('is_draft', 1)->first();

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
        $feed = ImportFeed::where('is_draft', 1)->first();
        if ($feed) {
            if ($feed->content_tag != $this->import_feed['content_tag']) {
                $feed->content_tag = $this->import_feed['content_tag'];
                $feed->save();
            }

            $this->readFeedFile();

            $feed = ImportFeed::where('is_draft', 1)->first();
            $this->refreshImportFeedState($feed->toArray());

            $this->emit('htmlDropdownMappingPreviewRefresh');
        }
    }

    public function readFeedFile()
    {
        $feed = ImportFeed::where('is_draft', 1)->first();
        if ($feed) {
            $feedFile = $feed->source_file_realpath;

            if (is_file($feedFile)) {

                $fileExt = pathinfo($feedFile, PATHINFO_EXTENSION);
                $read = $feed->readContentFromFile($feedFile, $fileExt);

                if ($read) {
                    $this->showTab('map');
                    session()->flash('successMessage', 'Feed is read successfully.');

                    // Read new feed
                    $feed = ImportFeed::where('is_draft', 1)->first();
                    $this->refreshImportFeedState($feed->toArray());

                } else {
                    session()->flash('errorMessage', 'No content found in feed file.');
                }
            } else {
                session()->flash('errorMessage', 'Can\'t read feed.');
            }
        }
    }

    public function upload()
    {
        $this->validate([
            'upload_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $uploadFilePath = $this->upload_file->store('import-export-tool');
        $fullFilePath = storage_path(). '/app/'.$uploadFilePath;

        $feed = ImportFeed::where('is_draft', 1)->first();

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
        $feed = ImportFeed::where('is_draft', 1)->first();
        if ($feed) {

            $feedMapToArray = new FeedMapToArray();
            $feedMapToArray->setImportFeedId($feed->id);
            $preparedData = $feedMapToArray->toArray();

            $feed->mapped_content = $preparedData;
            $feed->save();

            $this->refreshImportFeedState($feed->toArray());

            $this->showTab('import');
        }
    }

    public function import()
    {
        sleep(5);
    }

    public function mount()
    {
        $findImportFeed = ImportFeed::where('is_draft', 1)->first();

        if (!$findImportFeed) {
            $newDraftFeed = new ImportFeed();
            $newDraftFeed->is_draft = 1;
            $newDraftFeed->save();
            $findImportFeed = ImportFeed::where('is_draft', 1)->first();
        }

        if ($findImportFeed) {
            $this->refreshImportFeedState($findImportFeed->toArray());
        }
    }

    public function refreshImportFeedState($importFeed)
    {

        $this->import_feed = $importFeed;
        $this->import_feed['mapped_content_count'] = count($this->import_feed['mapped_content']);
        $this->import_feed['source_content_count'] = count($this->import_feed['source_content']);

        $this->importFeedId = $this->import_feed['id'];

        unset($this->import_feed['mapped_content']);
        unset($this->import_feed['source_content']);
    }

    public function render()
    {
        return view('import_export_tool::admin.import-wizard.index');
    }
}
