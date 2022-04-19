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

        $cacheTags = 'import_export_tool';

        $cacheXmlMappedArrayId = $this->import_feed_id.'_prepared_data_mapped';
        $cacheXmlMappedArrayGet = Cache::tags($cacheTags)->get($cacheXmlMappedArrayId);

        // Read xml as array
        $cacheXmlArrayId = $this->import_feed_id.'_prepared_data';
        $cacheXmlArrayGet = Cache::tags($cacheTags)->get($cacheXmlArrayId);
        if (empty($cacheXmlArrayGet) && empty($cacheXmlMappedArrayGet)) {
            $contentXml = file_get_contents($xmlFile);
            $newReader = new XmlToArray();
            $data = $newReader->readXml($contentXml);

            $iterratableData = Arr::get($data, $importFeed->content_tag);
            if (empty($iterratableData)) {
                $this->done = true;
                return false;
            }

            Cache::tags($cacheTags)->put($cacheXmlArrayId, $iterratableData);
            return; // next step
        }

        // Map and cache readed data from xml
        if (empty($cacheXmlMappedArrayGet)) {
            $mappedData = [];
            foreach ($cacheXmlArrayGet as $itemI => $item) {
                foreach ($importFeed->mapped_tags as $tagKey => $internalKey) {
                    $tagKey = str_replace(';', '.', $tagKey);
                    $tagKey = str_replace($importFeed->content_tag . '.', '', $tagKey);

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

            Cache::tags($cacheTags)->put($cacheXmlMappedArrayId, $preparedData);
            return; // next step
        }

        // Start importing cached data
        SessionStepper::setSessionId($this->import_feed_session_id);
        SessionStepper::nextStep();

        $writer = new DatabaseWriter();
        $writer->setLogger($this);
        $writer->setContent([
            'content'=>$cacheXmlMappedArrayGet
        ]);
        $writer->setOverwriteById(1);
        $writer->runWriterWithBatch();

        $log = $writer->getImportLog();
        $this->import_log = $log;

        if (isset($log['done']) && $log['done']) {
            $this->done = true;
        }

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
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_log['total_steps'] = $importFeed->split_to_parts;

        $this->import_feed_id = (int) $importFeedId;
        $this->import_feed_session_id = SessionStepper::generateSessionId($importFeed->split_to_parts);
    }

    public function render()
    {
        return view('import_export_tool::admin.livewire-start-importing-modal');
    }
}
