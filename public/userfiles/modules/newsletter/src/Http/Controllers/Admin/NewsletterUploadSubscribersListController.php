<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;


use MicroweberPackages\Modules\Newsletter\ImportSubscribersFileReader;
use Modules\FileManager\Http\Controllers\PluploadController;

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
