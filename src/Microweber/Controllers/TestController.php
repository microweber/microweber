<?php namespace Microweber\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
	function getIndex()
	{
		$a = new \Microweber\Install\WebserverInstaller;
		$a = $a->run();
		dd($a);
	}
}