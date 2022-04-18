<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Import\DatabaseWriter;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\App\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $importFeedNames = [];
        $getImportFeeds = ImportFeed::all();
        if ($getImportFeeds->count() > 0) {
            foreach ($getImportFeeds as $importFeed) {
                $importFeedNames[$importFeed->id] = $importFeed->name;
            }
        }

        return $this->view('import_export_tool::admin.index', ['import_feed_names'=>$importFeedNames]);
    }

    public function import($id)
    {
        return $this->view('import_export_tool::admin.import', ['import_feed_id'=>$id]);
    }

    public function importStart($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
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

            DatabaseSave::saveProduct($preparedItem);
        }

        dd($preparedData);

     //   SessionStepper::generateSessionId(2);

        SessionStepper::setSessionId('1650294908625d807cc97bb');
        SessionStepper::nextStep();

        $writer = new DatabaseWriter();
        $writer->setLogger($this);
        $writer->setContent([
            'content'=>$preparedData
        ]);
        $writer->setOverwriteById(1);
        $writer->runWriterWithBatch();

        $log = $writer->getImportLog();

        dd($log);
    }

    public function clearLog()
    {

    }

    public function setLogInfo($log)
    {

    }
}
