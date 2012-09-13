<?
$is_new = template_var('new_page');




$data  = array();
$data['id'] = 0;	
$data['content_type'] = 'page';	
$data['title'] = 'Title';	
$data['content_url'] = '';	
$data['is_active'] = 'y';	
$data['is_home'] = 'n';	
$data['content_subtype'] = 'dynamic';	
$data['description'] = '';	
$data['active_site_template'] = '';	
$data['content_subtype_value'] = '';	
$data['content_parent'] = 0;	
$data['content_layout_name'] = '';		
$data['content_layout_file'] = '';	

if($is_new == false){
	 
} else {
	 foreach($is_new as $k => $v){
		$data[$k] =  $v;	
		 
	 }
	
}