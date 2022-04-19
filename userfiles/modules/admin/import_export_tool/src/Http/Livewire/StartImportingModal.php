<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Import\DatabaseWriter;
use MicroweberPackages\Import\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class StartImportingModal extends ModalComponent
{
    public $done = false;
    public $import_log = [
        'current_step'=>0,
        'total_steps'=>10,
        'percentage'=>0,
    ];
    public $import_feed_session_id = false;
    public $import_feed;

    public function nextStep()
    {
        $xmlFile = base_path() . DS . $this->import_feed->source_file_realpath;
        if (!is_file($xmlFile)) {
            return redirect(route('admin.import-export-tool.index'));
        }

        if (empty($this->import_feed->mapped_content)) {
            $contentXml = file_get_contents($xmlFile);
            $newReader = new XmlToArray();
            $data = $newReader->readXml($contentXml);

            $iterratableData = Arr::get($data, $this->import_feed->content_tag);
            if (empty($iterratableData)) {
                $this->done = true;
                return false;
            }

            $mappedData = [];
            foreach ($iterratableData as $itemI => $item) {
                foreach ($this->import_feed->mapped_tags as $tagKey => $internalKey) {
                    $tagKey = str_replace(';', '.', $tagKey);
                    $tagKey = str_replace($this->import_feed->content_tag . '.', '', $tagKey);

                    $saveItem = Arr::get($item, $tagKey);
                    if (isset(ItemMapReader::$itemTypes[$internalKey])) {
                        if (ItemMapReader::$itemTypes[$internalKey] == ItemMapReader::ITEM_TYPE_ARRAY) {
                            $saveArrayItem = [];
                            $saveArrayItem[] = $saveItem;
                            $saveItem = $saveArrayItem;
                        }
                    }

                    $mappedData[$itemI][$internalKey] = $saveItem;
                }
            }

            $preparedData = [];
            foreach ($mappedData as $undotItem) {
                $preparedItem = Arr::undot($undotItem);
                $preparedData[] = $preparedItem;
            }

            $this->import_feed->mapped_content = $preparedData;
            $this->import_feed->save();
            return; // next step
        }

        // Start importing cached data
        SessionStepper::setSessionId($this->import_feed_session_id);
        SessionStepper::nextStep();

        $this->import_log['current_step'] = SessionStepper::currentStep();
        $this->import_log['percentage'] = SessionStepper::percentage();

        $totalItemsForSave = sizeof($this->import_feed->mapped_content);
        $totalItemsForBatch = (int) ceil($totalItemsForSave / $this->import_log['total_steps']);
        $itemsBatch = array_chunk($this->import_feed->mapped_content, $totalItemsForBatch);

        $selectBatch = ($this->import_log['current_step'] - 1);
        if (!isset($itemsBatch[$selectBatch])) {
            SessionStepper::finish();
        }

        if (SessionStepper::isFinished()) {
            $this->done = true;
            return array("success"=>"Done! All steps are finished.");
        }

        $success = array();
        foreach($itemsBatch[$selectBatch] as $item) {
            $success[] = DatabaseSave::saveProduct($item);
        }

        return $success;
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
        $this->import_feed = ImportFeed::where('id', $importFeedId)->first();
        if ($this->import_feed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_log['total_steps'] = $this->import_feed->split_to_parts;
        $this->import_feed_session_id = SessionStepper::generateSessionId($this->import_feed->split_to_parts);
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-start-importing-modal');
    }
}
