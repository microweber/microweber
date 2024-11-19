<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;

class ExportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
       return view('import_export_tool::admin.export-wizard-index');
    }

    public function deleteFile($id)
    {
        $findExportFeed = ExportFeed::where('id', $id)->first();
        if ($findExportFeed) {
            $findExportFeed->delete();
        }

        return redirect(route('admin.import-export-tool.index-exports'));
    }

    public function file($id)
    {


    }

}
