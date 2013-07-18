<?php
if (defined("MW_NO_BASE") == false) {
    define("MW_NO_BASE", 111111111);
}

//pure test without loading the mw functions
class PureTest  
{
    function __construct()
    {

    }

    function test()
    {

        
    }

    function index()
    {

        //you can use MW as plain ol MVC;

       debug_info();


    }


}