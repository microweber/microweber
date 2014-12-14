<?php namespace Microweber\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
	function getIndex()
	{
		$u = \User::find(1);
		echo '<pre>';
		dd($u->isAdmin);
	}
}