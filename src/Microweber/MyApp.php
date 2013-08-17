<?php

namespace Microweber;



class MyApp extends \Microweber\Application
{



    public function __construct($config = false)
    {


        $this->media = new  MyMedia;


    }


}

class MyMedia extends \Microweber\Media
{
    function thumbnail($src,$w=100)
    {


        return 'http://code.divshot.com/geo-bootstrap/img/test/mchammer.gif';
    }

}
