<?php
namespace mw;


class MwExample
{
    function __construct()
    {

    }

    static function test()
    {

        $cont = get_content('one=1');

        return $cont;
    }

    function index()
    {

        //you can use MW as plain ol MVC;

        print 'Hello from Example!';


    }


}