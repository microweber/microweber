<?php


namespace Microweber\Controllers;

use Microweber\View;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class DefaultController extends Controller
{


    public function index()
    {


        $is_installed = Config::get('microweber.is_installed');

        if (!$is_installed) {
            return $this->install();
        }


////        var_dump($connection);


//        $connection = mw()->config->get('database.connections');
//       // var_dump($connection);
//
//
////        $connection = Config::set('microweber.is_installed', 1);
////        var_dump($connection);
////
////
////        $connection = Config::get('microweber.is_installed');
////        var_dump($connection);
//
//        $connection = mw()->config->save();
//        var_dump($connection);
//        var_dump(__METHOD__);
//        exit;
    }

    public function install()
    {


        $view = MW_PATH . 'Views/install.php';

        $connection = Config::get('database.connections');
        $layout = new View($view);
        $is_installed = Config::get('microweber.is_installed');

        $layout->assign('connection', $connection);
        $layout->assign('done', $is_installed);
        $layout = $layout->__toString();


        return $layout;
    }

    public function api()
    {

        var_dump(__METHOD__);
        exit;
    }
}
