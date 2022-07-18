<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminDefaultController
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

        return $this->view('import_export_tool::admin.index', ['import_feed_names' => $importFeedNames]);
    }

    public function import($id)
    {
        return $this->view('import_export_tool::admin.import', ['import_feed_id' => $id]);
    }

    public function importStart($id) {

        $feedMapToArray = new FeedMapToArray();
        $feedMapToArray->setImportFeedId($id);
        $array = $feedMapToArray->toArray();

       DatabaseSave::savePost($array[0]);


    }
}
