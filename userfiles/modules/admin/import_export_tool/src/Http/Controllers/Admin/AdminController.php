<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends \MicroweberPackages\App\Http\Controllers\AdminController
{
    public function index(Request $request)
    {

        return $this->view('import_export_tool::admin.index', []);
    }
}
