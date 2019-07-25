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
    
    public function download($photoId) {
    	
    	$filename = media_uploads_path() . $photoId . '.jpg';
    	
    	$downloaded = mw()->http->url('http://imglib.microweberapi.com/download.php?photo_id='. $photoId)->download($filename);
    	
    	if ($downloaded) {
    		
    		
    		
    	}
    	
    	
    }
}