<?php $searchgoogle = $_POST ['searchgoogle'];
if ($searchgoogle != '') {
	$search_what = 'searchgoogle';
}

$searchadvanced = $_POST ['searchadvanced'];
if ($searchadvanced != '') {
	$search_what = 'searchadvanced';
}


$searchgooglemaps = $_POST ['searchgooglemaps'];
if ($searchgooglemaps != '') {
	$search_what = 'searchgooglemaps';
}

$searchsite = $_POST ['searchsite'];

if($searchsite != ''){
	$togo = site_url('search/keyword:').$searchsite ;
	//redirect($togo);
	header('Location: '. $togo);
	exit;
}

//search site by keyword:
$keyword = $this->core_model->getParamFromURL ( 'keyword' );
if ($keyword != '') {
	$search_what = 'searchsite';
	$search_for = $keyword;
}


$select_google_maps_from = $this->core_model->getParamFromURL ( 'select_google_maps' );

if($select_google_maps_from == 'y'){
	$search_what = 'searchgoogle';
}


if($select_google_maps_from == 'y'){
	$search_what = 'searchgoogle';
	$keyword = $this->core_model->getParamFromURL ( 'keywords' );
	$search_for = $keyword;
	$this->template ['search_for'] =$select_google_maps;



	if ($_COOKIES ['search_engine'] == "searchgoogle") {
		setcookie ( "search_engine", $search_what);
	}



}








switch ( $search_what) {
	case 'searchgoogle' :
		$this->template ['load_google_map'] = true;
		$this->template ['search_what'] = 'searchgoogle';
		$this->template ['search_for'] = $_POST ['searchgoogle'];
		//$this->template ['content_selected_tags'] = $tags;
		$this->load->vars ( $this->template );
		break;

	case 'searchadvanced' :
		//var_dump($_POST);
		$this->template ['load_google_map'] = true;
		$this->template ['search_what'] = 'searchadvanced';
		$this->template ['search_for'] = $_POST ['searchadvanced'];
		$this->template ['google_restricted_site_search'] = $_POST ['google_restricted_site_search'];


		$map_search_posts = array();
		$temp = array();
		$temp['selected_categories']  = $map_search_cats;
		$temp['have_original_link']  = 'y';
		$temp['content_type']  = 'post';
		$map_search_posts = $this->content_model->getContent($temp);
		$this->template ['google_restricted_site_search_posts'] = $map_search_posts;



		//$this->template ['content_selected_tags'] = $tags;
		$this->load->vars ( $this->template );
		break;


	case 'searchgooglemaps' :
		$this->template ['load_google_map'] = true;
		//var_dump($_POST);
		//get things on the map that we have in the DB
		$geo  = array();
		$geo['to_table'] = 'table_content';
		$geodata = $this->content_model->geoDataGet($geo);
		$geodata_items  = array();
		foreach($geodata as $item){
			if($item['to_table'] == 'table_content'){
				$geodata_item = $this->content_model->contentGetById($item['to_table_id']) ;
				$geodata_items[] = $geodata_item;
			}}
			//var_dump($geodata_items);
			$this->template ['geodata_items'] = $geodata_items;
			$this->template ['search_what'] = 'searchgooglemaps';
			$this->template ['search_for'] = $_POST ['searchgooglemaps'];
			$this->load->vars ( $this->template );
			break;


	default:
		$items_per_page = $this->content_model->optionsGetByKey ( 'default_items_per_page' );
		$items_per_page = intval ( $items_per_page );
		$curent_page = $this->core_model->getParamFromURL ( 'curent_page' );
		if (intval ( $curent_page ) < 1) {
			$curent_page = 1;
		}

		//var_dump($items_per_page);
		//exit;

		$page_start = ($curent_page - 1) * $items_per_page;
		$page_end = ($page_start) + $items_per_page;
		$search_data = array();
		$search_data['content_type'] = 'post';
		if($search_for != ''){
			$search_data['search_by_keyword'] = $search_for;
		}
		$search_data_criteria = $search_data;
		//var_dump($search_data_criteria, $page_start, $page_end);
		//exit;
		//getContent($data, $orderby = false, $limit = false, $count_only = false)
		$search_data = $this->content_model->getContent($search_data, $orderby = false, array ($page_start, $page_end ), $count_only = false);
		//var_dump($search_data);
		//exit();
		$this->template ['search_data'] = $search_data;




		$search_data_results_count = $this->content_model->getContent ( $search_data_criteria, false, array (0, 100 ), true );
		$search_data_content_pages_count = ceil ( $search_data_results_count / $items_per_page );
		//var_dump ( $results_count, $items_per_page );
		$this->template ['search_data_pages_count'] = $content_pages_count;
		$this->template ['search_data_pages_curent_page'] = $curent_page;
		//get paging urls
		$search_data_content_pages = $this->content_model->pagingPrepareUrls ( false, $search_data_content_pages_count );

		$this->template ['search_data_pages_links'] = $search_data_content_pages;




























		$this->template ['search_what'] = 'searchsite';
		$this->template ['search_for'] = $search_for;
		//$this->template ['content_selected_tags'] = $tags;
		$this->load->vars ( $this->template );










		break;

}

