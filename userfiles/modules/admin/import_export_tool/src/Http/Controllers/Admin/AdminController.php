<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{


    public function exports()
    {
        $exportFeeds = ExportFeed::where('is_draft', 0)->get();

        return view('import_export_tool::admin.index-exports', ['export_feeds' => $exportFeeds]);
    }

}
