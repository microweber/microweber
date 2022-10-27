<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
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
        $this->tab = 'upload';
        $this->importTo = $importTo;

        $findImportFeed = ImportFeed::where('is_draft', 1)->first();
        if (!$findImportFeed) {
            $findImportFeed = new ImportFeed();
            $findImportFeed->is_draft = 1;
        }

        $findImportFeed->name = 'Import ' . ucfirst($importTo);
        $findImportFeed->import_to = $importTo;
        $findImportFeed->save();
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
                    $this->tab = 'map';
                    session()->flash('successMessage', 'Feed is read successfully.');
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

    public function mount()
    {
        $findImportFeed = ImportFeed::where('is_draft', 1)->first();
        if ($findImportFeed) {
            $this->import_feed = $findImportFeed->toArray();
            $this->importFeedId = $findImportFeed->id;
        }
    }

    public function render()
    {
        return view('import_export_tool::admin.import-wizard.index');
    }
}
