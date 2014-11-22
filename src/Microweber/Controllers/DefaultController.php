<?php


namespace Microweber\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class DefaultController extends Controller
{


    public function index()
    {
        $connection = mw()->config->get('database.connections');
       // var_dump($connection);


//        $connection = Config::set('microweber.is_installed', 1);
//        var_dump($connection);
//
//
//        $connection = Config::get('microweber.is_installed');
//        var_dump($connection);

        $connection = mw()->config->save();
        var_dump($connection);
        var_dump(__METHOD__);
        exit;
    }

    public function api()
    {

        var_dump(__METHOD__);
        exit;
    }
}
