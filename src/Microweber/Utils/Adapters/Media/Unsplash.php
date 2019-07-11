<?php

namespace Microweber\Utils\Adapters\Media;

class Unsplash
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function search($keyword) {
    	
    	$json = mw()->http->url('http://imglib.microweberapi.com/index.php?search='. $keyword)->get();
    	$json = json_decode($json, TRUE);
    	
    	return $json;
    }
}