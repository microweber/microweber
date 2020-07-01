<?php

namespace Microweber\App\Http\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function getIndex()
    {
        $a = new \Microweber\App\Install\WebserverInstaller();
        $a = $a->run();
        dd($a);
    }
}
