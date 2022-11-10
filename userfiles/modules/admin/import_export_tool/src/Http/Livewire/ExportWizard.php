<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;

class ExportWizard extends Component
{
    public $queryString = ['exportType','exportFormat','tab'];
    public $tab = 'type';
    public $export_feed = [
        'name'=>'',
        'export_type'=>'',
        'export_format'=>''
    ];

    public $exportType;
    public $exportFormat;

    public function selectExportType($type)
    {
        $this->exportType = $type;
        $this->tab = 'format';
    }

    public function selectExportFormat($format)
    {
        $this->exportFormat = $format;
        $this->tab = 'export';
    }

    public function showTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $exportFeedId = request()->get('exportFeedId');
        $findExportFeed = false;
        if ($exportFeedId) {
            $findExportFeed = ExportFeed::where('id', $exportFeedId)->first();
        }

        if (!$findExportFeed) {
            $newDraftFeed = new ExportFeed();
            $newDraftFeed->is_draft = 1;
            $newDraftFeed->save();
            $findExportFeed = ExportFeed::where('id', $newDraftFeed->id)->first();
        }

        if ($findExportFeed) {
        //    $this->refreshImportFeedState($findExportFeed->toArray());
        }
    }

    public function render()
    {
        return view('import_export_tool::admin.export-wizard.index');
    }
}
