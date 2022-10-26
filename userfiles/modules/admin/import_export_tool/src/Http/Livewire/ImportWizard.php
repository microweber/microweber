<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;

class ImportWizard extends Component
{
    protected $queryString = ['tab', 'importTo'];

    public $tab = 'type';
    public $importTo = 'type';

    public function showTab($tab)
    {
        $this->tab = $tab;
    }

    public function selectImportTo($importTo)
    {
        $this->tab = 'upload';
        $this->importTo = $importTo;
    }

    public function render()
    {
        return view('import_export_tool::admin.import-wizard.index');
    }
}
