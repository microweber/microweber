<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ImportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $importTo = $request->get('import_to', false);
        if ($importTo) {
            return redirect(route('admin.import-export-tool.import-wizard-upload'));
        }

        return $this->view('import_export_tool::admin.import-wizard.index', [
            'tab'=>'type'
        ]);
    }

    public function upload(Request $request)
    {
        return $this->view('import_export_tool::admin.import-wizard.upload',[
            'tab'=>'upload'
        ]);
    }
}
