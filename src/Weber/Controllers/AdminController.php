<?php


namespace Weber\Controllers;

use Weber\View;
use Weber\Utils\Installer;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Module;

class AdminController extends Controller
{


    public function index()
    {
        $is_installed = Config::get('weber.is_installed');

        if (!$is_installed) {
            App::abort(403, 'Unauthorized action. Weber is not installed.');
        }
        $view = WB_PATH . 'Views/admin.php';

        $layout = new View($view);
        $layout = $layout->__toString();

        wb()->parser->process($layout);


        return $layout;

    }
}
