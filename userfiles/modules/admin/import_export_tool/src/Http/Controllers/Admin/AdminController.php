<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $importFeeds = ImportFeed::all();

        return $this->view('import_export_tool::admin.index', ['import_feeds' => $importFeeds]);
    }

    public function import($id)
    {
        return $this->view('import_export_tool::admin.import', ['import_feed_id' => $id]);
    }

    public function importWizard(Request $request)
    {

    }

    public function importStart($id) {

        $feedMapToArray = new FeedMapToArray();
        $feedMapToArray->setImportFeedId($id);
        $array = $feedMapToArray->toArray();

       DatabaseSave::savePost($array[0]);

    }
}
