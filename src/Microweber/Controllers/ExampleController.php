<?php

namespace Microweber\Controllers;

class ExampleController
{
    function __construct()
    {

    }

    function test()
    {

        $cont = get_content('one=1');

        print_r($cont);
    }

    function index()
    {

        //you can use MW as plain ol MVC;

        print 'Hello from The Example Controller!';


    }


}