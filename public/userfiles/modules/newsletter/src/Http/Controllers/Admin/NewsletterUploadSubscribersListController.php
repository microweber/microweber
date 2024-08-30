<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;


use Illuminate\Support\Facades\App;
use MicroweberPackages\FileManager\Http\Controllers\PluploadController;
use MicroweberPackages\Modules\Newsletter\ImportSubscribersFileReader;

class NewsletterUploadSubscribersListController extends PluploadController {

    public $allowedFileTypes = [
        'xlsx','xls','csv'
    ];
    public $returnPathResponse = false;

    public function getUploadPath()
    {
        return ImportSubscribersFileReader::getImportTempPath() . 'uploaded_files';
    }




}
