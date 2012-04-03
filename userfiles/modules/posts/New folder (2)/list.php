<?

/**
 * 
 * 
 * Generic module to display posts list.
 * @author Peter Ivanov
 * @package content


Example:
 @example:  

 <microweber module="posts/list" file="posts_list_games" category="<? print  $category['id'] ?>"></microweber>
 


 //params for the data
 @param $file | tries to load this file from the modules/posts/views/ dir  | default:false
 @param $categories | array of categories | default:false
 @param $category | integer of category | default:false
 @param $limit | how many items per page | default:30
 
 @param $no_results_text | prints this if there are no results | default:false
 @param $auto_login | if true will login automaticly the usera after registration | default:false
 
 

 */

?>
<?php

 

    $post_params= array();
	if($display){
	$post_params['display']= $display;
	}
	
	if($category){
		$category = explode(',', $category);
	$post_params['selected_categories'] = $category;
	}
	
	if(!empty($categories)){
	$post_params['selected_categories'] = $categories;
	}
	
	//
	if(!$limit){
		$limit = 30;
	}
	if($limit){
	$post_params['items_per_page'] = $limit;
	}
	
	
	if($created_by){
	 
	$post_params['created_by'] = $created_by;
	}
	
	if($voted_by){
	 
	$post_params['voted_by'] = $voted_by;
	}
	
	 
	
	if($keyword){
	 
	$post_params['keyword'] = $keyword;
	}
	
	if($file){
	 
	$post_params['file'] = $file;
	}
	
	
	if($curent_page){
	$post_params['curent_page'] = $curent_page;
	} else {
		
		$curent_page = url_param('curent_page');
		if(intval($curent_page)!= false){
			$post_params['curent_page'] = $curent_page;
		} else{
		$post_params['curent_page'] = 1;
		}
	}
	
	
	$this->appvar->set('items_per_page', $post_params['items_per_page']); 
	$this->appvar->set('curent_page', $post_params['curent_page']); 
 
	$posts = get_posts($post_params);
 

	?>
<? if(empty($posts )): ?>

<? if(($no_results_text )): ?>
<? print $no_results_text ; ?>
<? endif; ?>
<?  else : ?>
<? if(!$display and !$file): ?>

<ul class="parent_list">
  <? foreach($posts as $post): ?>
  <li> <a href="<? print post_link($post['id']); ?>" class="img" style="background-image: url('<? print thumbnail($post['id'], 150) ?>')"><span></span></a> <strong><a href="<? print post_link($post['id']); ?>"><? print $post['content_title'] ?></a></strong>
    <p><? print $post['content_description'];  ?></p>
  </li>
  <? endforeach; ?>
</ul>
<? endif; ?>
<? endif; ?>
