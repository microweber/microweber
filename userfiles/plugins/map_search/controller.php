<?php $load_google_map = true;
$this->template ['load_google_map'] = $load_google_map;





if ($_COOKIES ['search_engine'] != 'search-map') {
	setcookie ( "search_engine", 'search-map' );
}
$map_search = new Map_search_model ( );

//$map_search_plugin_data = $map_search->getContent ();


$this->template ['search_what'] = 'searchgooglemaps';
$this->template ['search_for'] = $_REQUEST ['searchgooglemaps'];
$this->template ['google_restricted_site_search_cateogies'] = $_REQUEST ['google_restricted_site_search_cateogies'];


$map_search_search_country = $this->core_model->getParamFromURL ( 'country' );
$map_search_search_city = $this->core_model->getParamFromURL ( 'city' );
$map_search_search_street = $this->core_model->getParamFromURL ( 'street' );
$map_search_search_street_n = $this->core_model->getParamFromURL ( 'strn' );

if($map_search_search_country == false){
$map_search_search_country = 'Bulgaria';	
}

$this->template ['map_search_search_country'] = $map_search_search_country;
$this->template ['map_search_search_city'] = $map_search_search_city;

$this->template ['map_search_search_street'] = $map_search_search_street;
$this->template ['map_search_search_street_n'] = $map_search_search_street_n;





$this->template ['map_search_plugin_data'] = $map_search_plugin_data;
$this->load->vars ( $this->template );

if ($_POST) {
	$togo = $link = $this->content_model->getContentURLById ( $page ['id'] );
	if ($_POST ['mapsearch-country']) {
		$togo_country = '/country:' . mb_trim ( $_POST ['mapsearch-country'] );
	} else {
		$togo_country = false;
	}
	
	if ($_POST ['map_search_city']) {
		$togo_city = '/city:' . mb_trim ( $_POST ['map_search_city'] );
	} else {
		$togo_city = false;
	}
	
	if ($_POST ['map_seach_street']) {
		$togo_street = '/street:' . mb_trim ( $_POST ['map_seach_street'] );
	} else {
		$togo_street = false;
	}
	
	if ($_POST ['map_seach_street_n']) {
		$togo_street_n = '/strn:' . mb_trim ( $_POST ['map_seach_street_n'] );
	} else {
		$togo_street_n = false;
	}
	
	$togo = $togo . $togo_country . $togo_city . $togo_street . $togo_street_n;
	$togo = reduce_double_slashes ( $togo );
	header('Location: '.$togo);
}






//get posts with external URLS




//}









//var_dump ( $map_search_plugin_data );

//print 'map_search plugin';


//var_dump ( $this->core_model->plugins_isRunning ('map_search') );
