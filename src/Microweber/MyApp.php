<?php

namespace Microweber;


class MyApp extends \Microweber\Application
{


    public $content;

    public function __construct($config = false)
    {
        $this->content = new MyContent();

    }


}

class MyContent extends \Microweber\Content
{

    function ______get_layout($params)
    {
        print 'my layout return logic ';
        var_dump($params);
        return 'me_path_to_layout.php';
    }
}


