<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportWizard extends Component
{
    protected $queryString = ['tab', 'importTo'];

    public $tab = 'type';
    public $importTo = 'type';

    public $import_feed = [];

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
