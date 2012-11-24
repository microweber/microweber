<?
$is_new = template_var('new_page');




$data  = array();
$data['id'] = 0;	
$data['content_type'] = 'page';	
$data['title'] = 'Title';	
$data['url'] = '';	

$data['thumbnail'] = '';	 

$data['is_active'] = 'y';	
$data['is_home'] = 'n';	

$data['is_shop'] = 'n';	


$data['subtype'] = 'static';	
$data['description'] = '';	
$data['active_site_template'] = '';	
$data['subtype_value'] = '';	
$data['parent'] = 0;	
$data['layout_name'] = '';		
$data['layout_file'] = '';	

if($is_new == false){
	 
} else {
	 foreach($is_new as $k => $v){
		$data[$k] =  $v;	
		 
	 }
	
}