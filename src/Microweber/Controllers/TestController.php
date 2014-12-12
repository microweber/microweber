<?php namespace Microweber\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller {
	function getIndex()
	{
		return \Hash::make('1');
	}
}