<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin;

use MicroweberPackages\FileManager\Http\Controllers\PluploadController;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class UploadFeedController extends PluploadController
{
    public $allowedFileTypes = [
      'phtml', 'php', 'json', 'xml','xlsx','xls','csv'
    ];
    public $returnPathResponse = false;

    public function getUploadPath()
    {
        return ImportFeed::getImportTempPath();
    }

}
