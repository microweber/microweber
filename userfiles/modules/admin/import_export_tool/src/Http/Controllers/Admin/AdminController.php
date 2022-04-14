<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class AdminController extends \MicroweberPackages\App\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('import_export_tool::admin.index', []);
    }

    public function import($id)
    {
        return $this->view('import_export_tool::admin.import', ['import_feed_id'=>$id]);
    }
}
