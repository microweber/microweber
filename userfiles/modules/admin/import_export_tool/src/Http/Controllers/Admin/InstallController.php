<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class InstallController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return view('import_export_tool::admin.install');
    }
}
