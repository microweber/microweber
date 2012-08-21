

<?

/*
params

append_to_link="/editmode:y"
 
 
 
  */


 
if(!isset($params['link'])){
	if(isset($params['append_to_link'])){
		$append_to_link = $params['append_to_link'];
	} else {
		$append_to_link = '';
	}
	 
	$params['link'] = '<a href="{link}'.$append_to_link.'">{content_title}</a>';
	
}
 pages_tree($params) ?>


 