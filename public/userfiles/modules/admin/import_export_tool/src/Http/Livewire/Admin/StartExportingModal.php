<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Modules\Admin\ImportExportTool\ExportFeedFromDatabase;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;

class StartExportingModal extends AdminModalComponent
{
    public $error = false;
    public $done = false;
    public $export_log = [
        'current_step'=>0,
        'total_steps'=>0,
        'percentage'=>0,
    ];
    public $export_feed_session_id = false;
    public $export_feed;
    public $export_feed_filename = '';
    public $download_file = '';

    public $listeners = [
        'exportToolNextStep'=>'nextStep'
    ];

    public function nextStep()
    {
        SessionStepper::setSessionId($this->export_feed_session_id);
        SessionStepper::nextStep();

        $this->export_log['current_step'] = SessionStepper::currentStep();
        $this->export_log['percentage'] = SessionStepper::percentage();

        $export = new ExportFeedFromDatabase();

        $export->setBatchStep($this->export_log['current_step']);
        $export->setSplitToParts($this->export_feed->split_to_parts);

        $export->setExportFeedId($this->export_feed->id);

        $exportStatus = $export->start();

        if (isset($exportStatus['error'])) {
            $this->error = $exportStatus['error'];
            return [];
        }

        if (isset($exportStatus['finished']) && $exportStatus['finished']) {
            $this->download_file = $exportStatus['file'];
            $this->export_feed_filename = $exportStatus['filename'];
            $this->done = true;
            return array("success"=>"Done! All steps are finished.");
        }

        $this->dispatch('nextStepCompleted', []);

        return [];
    }

    public function setLogInfo($log)
    {
        $this->logDetails = $log;
    }

    public function mount($exportFeedId = false)
    {
        if(!$exportFeedId){
            return;
        }

        $this->export_feed = ExportFeed::where('id', $exportFeedId)->first();
        if ($this->export_feed == null) {
            return redirect(route('admin.import-export-tool.index-export'));
        }

        $splitToParts = (int) $this->export_feed->split_to_parts;
        $this->export_log['total_steps'] = $splitToParts;
        $this->export_feed_session_id = SessionStepper::generateSessionId($splitToParts);

    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-start-exporting-modal');
    }
}
