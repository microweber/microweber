<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use Modules\FileManager\Http\Controllers\PluploadController;

class UploadFeedController extends PluploadController
{
    public $allowedFileTypes = [
        'xml','xlsx','xls','csv'
    ];
    public $returnPathResponse = false;

    public function getUploadPath()
    {
        return ImportFeed::getImportTempPath() . 'uploaded_files';
    }

}
