<?php

namespace Microweber\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function index($params)
    {
        return 'Hello from TestController@index';
    }


   
}
