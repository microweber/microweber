<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportWizard extends Component
{
    use WithFileUploads;

    protected $queryString = ['tab', 'importTo'];

    public $tab = 'type';
    public $importTo = 'type';

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
        $sourceFile = $this->import_feed['source_file'];

        $feed = ImportFeed::where('is_draft', 1)->first();

        if ($feed->downloadFeed($sourceFile)) {
           // $this->tab = 'map';
            session()->flash('message', 'Feed is downloaded successfully.');
            $this->dispatchBrowserEvent('read-feed-from-file');
        }

        return ['downloaded' => false];
    }

    public $log;
    public function readFeedFile()
    {
        $feed = ImportFeed::where('is_draft', 1)->first();
        if ($feed) {
            $feedFile = $feed->source_file_realpath;
            $fileExt = pathinfo($feedFile,PATHINFO_EXTENSION);
            $feed->readFeedFromFile($feedFile, $fileExt);
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

        $feed->source_type = 'upload_file';
        $feed->source_file = $uploadFilePath;
        $feed->source_file_realpath = $fullFilePath;
        $feed->last_downloaded_date = Carbon::now();
        $feed->save();

        // $this->tab = 'import';
        session()->flash('message', 'Feed is uploaded successfully.');

    }

    public function mount()
    {
        $findImportFeed = ImportFeed::where('is_draft', 1)->first();
        if ($findImportFeed) {
            $this->import_feed = $findImportFeed->toArray();
        }
    }

    public function render()
    {
        return view('import_export_tool::admin.import-wizard.index');
    }
}
