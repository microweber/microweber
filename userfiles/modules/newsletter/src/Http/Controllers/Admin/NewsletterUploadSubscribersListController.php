<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;


use Illuminate\Support\Facades\App;
use MicroweberPackages\FileManager\Http\Controllers\PluploadController;

class NewsletterUploadSubscribersListController extends PluploadController {

    public $allowedFileTypes = [
        'xlsx','xls','csv'
    ];
    public $returnPathResponse = false;

    public function getUploadPath()
    {
        return $this->getImportTempPath() . 'uploaded_files';
    }

    public function getImportTempPath()
    {
        $environment = App::environment();
        $folder = storage_path('newsletter_subscribers_list/') . ('default' . DIRECTORY_SEPARATOR);

        if(defined('MW_IS_MULTISITE') and MW_IS_MULTISITE) {
            $folder = storage_path('newsletter_subscribers_list/') . ($environment . DIRECTORY_SEPARATOR);
        }

        if (!is_dir($folder)) {
            mkdir_recursive($folder);
        }

        return $folder;
    }



}
