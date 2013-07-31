<?php

namespace Microweber;



class MyApp extends \Microweber\Application
{



    public function __construct($config = false)
    {


        $this->media = new  MyMedia;
//d($this->media);
        // $this->media = new MyMedia;
        //. $this->media= new MyMedia;

    }


}

class MyMedia extends \Microweber\Media
{
    function thumbnail($src,$w=100)
    {


        return 'http://54.225.125.50/geo-bootstrap-master/img/test/mchammer.gif';
    }

}
