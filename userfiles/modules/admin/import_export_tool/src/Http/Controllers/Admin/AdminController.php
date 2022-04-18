<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

        $preparedData = [];
        foreach ($iterratableData as $itemI=>$item) {
            foreach($importFeed->mapped_tags as $tagKey=>$internalKey){
                $tagKey = str_replace(';','.', $tagKey);
                $tagKey = str_replace($importFeed->content_tag.'.', '', $tagKey);
                $preparedData[$itemI][$internalKey] = Arr::get($item, $tagKey);

            }
        }

        dd($preparedData);
    }
}
