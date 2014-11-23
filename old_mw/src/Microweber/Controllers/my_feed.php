<?php




class my_feed
{
    function __construct()
    {

    }

    function test()
    {

        $cont = get_content('one=1');
        d($cont);
        return $cont;
    }

    function index()
    {

        //you can use MW as plain ol MVC;

        print 'Hello from The Example Controller!';


    }


}