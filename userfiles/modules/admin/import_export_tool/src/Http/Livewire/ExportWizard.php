<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ExportWizard extends Component
{
    public $exportFeedId;
    public $queryString = ['exportFeedId','exportType','exportFormat','tab'];
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

        $findExportFeed = ExportFeed::where('id', $this->export_feed['id'])->first();
        if ($findExportFeed) {
            $findExportFeed->export_type = $type;
            $findExportFeed->save();

            $this->export_feed = $findExportFeed->toArray();
        }
    }

    public function selectExportFormat($format)
    {
        $this->exportFormat = $format;
        $this->tab = 'export';

        $findExportFeed = ExportFeed::where('id', $this->export_feed['id'])->first();
        if ($findExportFeed) {
            $findExportFeed->export_format = $format;
            $findExportFeed->is_draft = 0;
            $findExportFeed->save();

            $this->export_feed = $findExportFeed->toArray();
        }
    }

    public function showTab($tab)
    {
        $this->tab = $tab;
    }

    public function updatedExportFeed()
    {
        $findExportFeed = ExportFeed::where('id', $this->export_feed['id'])->first();
        if ($findExportFeed) {
            $findExportFeed->split_to_parts = $this->export_feed['split_to_parts'];
            $findExportFeed->is_draft = 0;
            $findExportFeed->save();
        }
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
            $newDraftFeed->split_to_parts = 5;
            $newDraftFeed->is_draft = 1;
            $newDraftFeed->save();

            $findExportFeed = ExportFeed::where('id', $newDraftFeed->id)->first();
        }

        if ($findExportFeed) {
            $this->export_feed = $findExportFeed->toArray();
            $this->exportFeedId = $findExportFeed->id;
        }
    }

    public function render()
    {
        return view('import_export_tool::admin.export-wizard.index');
    }
}
