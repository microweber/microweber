<?php

namespace Modules\Updater\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use MicroweberPackages\Admin\Http\Controllers\AdminController;

class UpdaterController extends AdminController
{


    public function updateFromCli($branch = 'master')
    {

        throw new \Exception(' updateFromCli is not implemented yet');
     }

}
