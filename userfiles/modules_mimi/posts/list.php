<?php

/*

type: module

name: Posts list

description: Module to display posts list.


param_file: Module to display posts list.






*/



?>
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
	} else {
		
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
	
	if($params['tn_size'] != false){
		$tn_s = $params['tn_size'];
		
		
	} else {
		
		 $tn_s = 150;
	}
	//$this->appvar->set('items_per_page', $post_params['items_per_page']); 
	//$this->appvar->set('curent_page', $post_params['curent_page']); 
 
	$posts = get_posts($post_params);


	?>
    
 
    
<? if(empty($posts )): ?>
<? if(($no_results_text )): ?>
<? print $no_results_text ; ?>
<? endif; ?>
<?  else : ?>
<? if($file): ?>
<? //foreach($posts['posts'] as $post): ?>
<?
if(stristr($file, '.php') == false){
	
	$file = $file.'.php';
}
	$try_file1 = TEMPLATE_DIR . $file;
	include($try_file1);
//$this->template ['posts'] = $posts['posts'];
//$this->template ['data'] = $posts;
//				$this->load->vars ( $this->template );
//				
//				$content_filename = $this->load->file ( $try_file1, true );
//				print $content_filename;
?>
<? //endforeach; ?>
<?  else : ?>
<? if(!$display and !$file): ?>

<ul class="posts-list">
  <? foreach($posts['posts'] as $post): ?>
  <li itemscope itemtype="http://schema.org/Article" class="single-post"> <strong ><a  class="post-title"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a></strong>
    <div class="single-post-info"> <span class="single-post-author">by <span itemprop="author"><? print user_name($post['created_by']); ?></span></span> | <span class="single-post-date">published on <span itemprop="datePublished"><? print ($post['created_on']); ?></span></span> </div>
    
    
    
    <? if( $tn_s  != 'none'): ?>
    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="img" style="background-image: url('<? print thumbnail($post['id'], $tn_s) ?>')"><span></span></a>
    <? endif; ?>
    
    <p class="single-post-description" itemprop="description"><? print $post['content_description'];  ?></p>
    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="single-post-more-link">Read more</a> </li>
  <? endforeach; ?>
</ul>
<? endif; ?>
<? endif; ?>
<? endif; ?>
