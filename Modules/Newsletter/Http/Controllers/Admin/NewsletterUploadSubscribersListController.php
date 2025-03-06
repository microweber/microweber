<?php
namespace Modules\Newsletter\Http\Controllers\Admin;


use Modules\Newsletter\Services\ImportSubscribersFileReader;
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
