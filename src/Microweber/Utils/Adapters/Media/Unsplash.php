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

    public function search($keyword, $page = 1) {
    	
    	$json = mw()->http->url('http://imglib.microweberapi.com/index.php?search='. $keyword . '&page=' . $page)->get();
    	$json = json_decode($json, TRUE);
    	
    	return $json;
    }
    
    public function download($photoId) { 
    	
    	$filename = media_uploads_path() . $photoId . '.jpg';
    	$filenameUrl = media_uploads_url() . $photoId . '.jpg';
    	
    	$imageUrl = 'http://imglib.microweberapi.com/download.php?photo_id='. $photoId; 
    	//$imageUrl = 'https://images.unsplash.com/photo-1504215680853-026ed2a45def?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2134&q=80';
    	
    	$downloaded = mw()->http->url($imageUrl)->download($filename); 
    	
    	if ($downloaded && is_file($filename)) {
    		
    		$tn_params = array();
    		$tn_params['width'] = 1600;    		
    		$tn_params['src'] = $filenameUrl;
    		$tn_params['return_cache_path'] = true;
    		$urlThumbnail = mw()->media_manager->thumbnail_img($tn_params);
    		
    		$urlThumbnail = dir2url($urlThumbnail);
     
    		return $urlThumbnail;
    	}
    	
    	
    }
}