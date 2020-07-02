<?php



namespace MicroweberPackages\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function getIndex()
    {
        return;

        $a = new \MicroweberPackages\Install\WebserverInstaller();
        $a = $a->run();
     
    }
}
