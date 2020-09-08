<?php



namespace Microweber\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function getIndex()
    {
        return;

        $a = new \Microweber\Install\WebserverInstaller();
        $a = $a->run();
     
    }
}
