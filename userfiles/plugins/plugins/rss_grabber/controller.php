<?php $searchgoogle = $_POST ['searchgoogle'];
if ($searchgoogle != '') {
	$search_what = 'searchgoogle';
}

$searchadvanced = $_POST ['searchadvanced'];
if ($searchadvanced != '') {
	$search_what = 'searchadvanced';
}

switch ( $search_what) {
	case 'searchgoogle' :
		$this->template ['search_what'] = 'searchgoogle';
		$this->template ['search_for'] = $_POST ['searchgoogle'];
		//$this->template ['content_selected_tags'] = $tags;
		$this->load->vars ( $this->template );
	break;
	
	case 'searchadvanced' :
		//var_dump($_POST);
		$this->template ['search_what'] = 'searchadvanced';
		$this->template ['search_for'] = $_POST ['searchadvanced'];
		$this->template ['google_restricted_site_search'] = $_POST ['google_restricted_site_search'];
		
		//$this->template ['content_selected_tags'] = $tags;
		$this->load->vars ( $this->template );
	break;
	
	
	
	
}

//print $searchgoogle;   


?>