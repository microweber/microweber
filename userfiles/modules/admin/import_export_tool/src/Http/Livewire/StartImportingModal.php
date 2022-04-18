<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Illuminate\Support\Arr;
use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Import\DatabaseWriter;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class StartImportingModal extends ModalComponent
{
    public $import_feed_session_id = false;
    public $import_feed_id;

    public function nextStep()
    {
        $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $xmlFile = base_path() . DS . $importFeed->source_file_realpath;
        if (!is_file($xmlFile)) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $contentXml = file_get_contents($xmlFile);

        $newReader = new XmlToArray();
        $data = $newReader->readXml($contentXml);
        $iterratableData = Arr::get($data,$importFeed->content_tag);

        $mappedData = [];
        foreach ($iterratableData as $itemI=>$item) {
            foreach($importFeed->mapped_tags as $tagKey=>$internalKey) {
                $tagKey = str_replace(';', '.', $tagKey);
                $tagKey = str_replace($importFeed->content_tag . '.', '', $tagKey);
                $mappedData[$itemI][$internalKey] = Arr::get($item, $tagKey);
            }
        }

        $preparedData = [];
        foreach ($mappedData as $undotItem) {
            $preparedItem = Arr::undot($undotItem);
            $preparedData[] = $preparedItem;
        }

        SessionStepper::setSessionId($this->import_feed_session_id);
        SessionStepper::nextStep();

        $writer = new DatabaseWriter();
        $writer->setLogger($this);
        $writer->setContent([
            'content'=>$preparedData
        ]);
        $writer->setOverwriteById(1);
        $writer->runWriterWithBatch();

        $log = $writer->getImportLog();

    }

    public function clearLog()
    {

    }

    public function setLogInfo($log)
    {
        $this->logDetails = $log;
    }

    public function mount($importFeedId)
    {
        $this->import_feed_id = (int) $importFeedId;
        $this->import_feed_session_id = SessionStepper::generateSessionId(6);
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-start-importing-modal');
    }
}
