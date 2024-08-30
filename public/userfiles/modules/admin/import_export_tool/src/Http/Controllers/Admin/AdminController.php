<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $importFeeds = ImportFeed::get();

        return view('import_export_tool::admin.index', ['import_feeds' => $importFeeds]);
    }

    public function exports()
    {
        $exportFeeds = ExportFeed::where('is_draft', 0)->get();

        return view('import_export_tool::admin.index-exports', ['export_feeds' => $exportFeeds]);
    }

    public function importDelete($id)
    {
        $findImportFeed = ImportFeed::where('id', $id)->first();
        if ($findImportFeed) {
            $findImportFeed->delete();
        }

        return redirect(route('admin.import-export-tool.index'));
    }


    /*   public function import($id)
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

        }*/

}
