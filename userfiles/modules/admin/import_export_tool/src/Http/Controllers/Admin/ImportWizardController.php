<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ImportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('import_export_tool::admin.render-livewire', [
            'component'=>'import_export_tool::import_wizard'
        ]);
    }
}
