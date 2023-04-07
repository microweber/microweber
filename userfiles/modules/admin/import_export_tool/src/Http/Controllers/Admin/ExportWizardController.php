<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Export\Formats\CsvExport;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Export\Formats\XmlExport;
use MicroweberPackages\Modules\Admin\ImportExportTool\BuildCategoryTree;
use MicroweberPackages\Modules\Admin\ImportExportTool\BuildProductCategoryTree;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

class ExportWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return view('import_export_tool::admin.render-livewire', [
            'component'=>'import_export_tool::export_wizard'
        ]);
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
