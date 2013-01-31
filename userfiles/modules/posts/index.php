<?
  

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'curent_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {
   
}

 $paging_param = 'curent_page';
if (isset($post_params['paging_param'])) {
	$paging_param = $post_params['paging_param'];
}


if (isset($params['curent_page'])) {
	$curent_page = $params['curent_page'];
} else {
 $curent_page_from_url = url_param('curent_page');	
  
 if($curent_page_from_url != false){
	 	$curent_page = $curent_page_from_url;
 }
}

if (isset($post_params['data-page-number'])) {

    $post_params['curent_page'] = $post_params['data-page-number'];
    unset($post_params['data-page-number']);
}



if (isset($post_params['data-category-id'])) {

    $post_params['category'] = $post_params['data-category-id'];
    unset($post_params['data-category-id']);
}


if(!isset($config['template_file'])){
 
//$config['template'] = get_option('data-template', $config['id']);
	//$config['template_file'] = 
}



if (isset($params['data-paging-param'])) {

    $paging_param = $params['data-paging-param'];

}





$show_fields = false;
if (isset($post_params['data-show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['data-show'];
} else {
    $show_fields = get_option('data-show', $params['id']);
}

if ($show_fields != false and is_string($show_fields)) {
    $show_fields = explode(',', $show_fields);
}





if (!isset($post_params['data-limit'])) {
    $post_params['limit'] = get_option('data-limit', $params['id']);
}
$cfg_page_id = get_option('data-page-id', $params['id']);
if ($cfg_page_id == false and isset($post_params['data-page-id'])) {
     $cfg_page_id =   intval($post_params['data-page-id']);
} else {
   // $cfg_page_id = get_option('data-page-id', $params['id']);

}

	if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
		$sub_cats = array();
		
			$str0 = 'table=table_taxonomy&limit=1000&data_type=category&what=categories&' . 'parent_id=[int]0&to_table_id=' . $cfg_page_id;
		$page_categories = get($str0);
		//d($page_categories);
		if(isarr($page_categories)){
			foreach ($page_categories as $item_cat){
			$sub_cats[] = $item_cat['id'];
			$more =    get_category_children($item_cat['id']);
			if(isarr($more)){
				foreach ($more as $item_more_subcat){
					$sub_cats[] = $item_more_subcat;
				}
			}
		//	d($more);
			}
		}
		
				
						if(empty($sub_cats)){
						
						$par_page = get_content_by_id($cfg_page_id);
						if(isset($par_page['subtype']) and strval($par_page['subtype']) == 'dynamic' and isset($par_page['subtype_value']) and intval(trim($par_page['subtype_value'])) > 0){
					  $sub_cats = get_category_children($par_page['subtype_value']);
					  if(!empty($sub_cats)){
							$sub_cats = implode(',',$sub_cats);
							 
							$post_params['category'] = $par_page['subtype_value'].','.$sub_cats;
					 
						} else {
							$post_params['category'] = $par_page['subtype_value'];
						}
					  } 
					  	
						}
						
						
						
						
						 $post_params['parent'] = $cfg_page_id;	
						
					  
						 
					
		
		
	}  
	
	
	
	
	
$tn_size = array('150');

if (isset($post_params['data-thumbnail-size'])) {
    $temp = explode('x', strtolower($post_params['data-thumbnail-size']));
    if (!empty($temp)) {
        $tn_size = $temp;
    }
} else {
    $cfg_page_item = get_option('data-thumbnail-size', $params['id']);
    if ($cfg_page_item != false) {
        $temp = explode('x', strtolower($cfg_page_item));

        if (!empty($temp)) {
            $tn_size = $temp;
        }
    }
}

$character_limit = 120;
$cfg_character_limit = get_option('data-character-limit', $params['id']);
if ($cfg_character_limit != false and trim($cfg_character_limit) != '') {
	$character_limit = intval($cfg_character_limit);
}

if ($show_fields == false) {
//$show_fields = array('thumbnail', 'title', 'description', 'read_more'); 
}

if(is_arr($show_fields)){

  $show_fields = array_trim( $show_fields);

}
 
if(isset($curent_page) and intval($curent_page) > 0){
	$post_params['curent_page'] = intval($curent_page);	

}

// $post_params['debug'] = 'posts';
$post_params['content_type'] = 'post';	
if(isset($params['is_shop'])){
	$post_params['subtype'] = 'product';
	unset($post_params['is_shop']);
} else {
 $post_params['subtype'] = 'post';
}


$content   = get_content($post_params);
$data = array();

if (!empty($content)){
// $data = $content;
//  if(!empty($show_fields)){
	  
	  foreach ($content as $item){
		  $iu = get_picture($item['id'], $for = 'post', $full = false);
			if($iu != false){
				 $item['image'] = $iu;
			} else {
				 $item['image'] = false;
			}
			$item['link'] = content_link($item['id']);
			if(!isset( $item['description']) or $item['description'] == ''){
				if(isset( $item['content']) and $item['content'] != ''){
					$item['description'] = character_limiter(strip_tags( $item['content']),$character_limit);
				}
 			}
			
			
			
if($post_params['subtype'] == 'product'){
$item['prices'] = get_custom_fields("field_type=price&for=content&for_id=".$item['id']);	
 
} else {
$item['prices'] = false;	
}
			
			
		 $data[] = $item;
	 }
// } 
}


 
$post_params_paging = $post_params;
$post_params_paging['page_count'] = true;

$cfg_data_hide_paging = get_option('data-hide-paging', $params['id']);


if($cfg_data_hide_paging != 'y'){
$pages_of_posts = get_content($post_params_paging);
$pages_count = intval($pages_of_posts);	
} else {
	$pages_count  = 0;
}

$paging_links  = false;
if (intval($pages_count) > 1){
	$paging_links = paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); 
	
} 

$read_more_text = get_option('data-read-more-text',$params['id']);



$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
	$module_template =$params['template'];
} 



 
 
if($module_template != false){
		$template_file = module_templates( $config['module'], $module_template);

} else {
		$template_file = module_templates( $config['module'], 'default');

}

//d($module_template );
if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} else {
	
	print 'No default template for '.  $config['module'] .' is found';
}

