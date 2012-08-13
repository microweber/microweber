<h1 class="title_product pink_color font_size_18"><? print $post['content_title'] ?></h1>
  <br/>
  
  <div class="rounded_box">
  <div class="news_small_box">
    <div class="text"><? print $post['the_content_body'] ?> <span class="clener"></span>
    
    <br />
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=156746347726744&amp;xfbml=1"></script><fb:like href="<? print post_link($post['id']);?>" send="true" width="350" show_faces="false" action="recommend" font="arial"></fb:like></td>
    
    
    
    <td>
    
    <a href="http://svejo.net/submit/?url=<? print post_link($post['id']);?>"
     data-url="<? print post_link($post['id']);?>"
     data-type="standard"
     id="svejo-button">Добави в Svejo</a>
<script type="text/javascript" src="http://svejo.net/javascripts/svejo-button.js"></script>
    
    </td>
    
  </tr>
</table>
<br />
<br />
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:comments href="<? print post_link($post['id']);?>" num_posts="10" width="800"></fb:comments>
    
     </div>
    
  </div>
  <div class="lt"></div>
  <div class="rt"></div>
  <div class="lb"></div>
  <div class="rb"></div>
</div>








<? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
        <? if(!empty($shop_page)): ?>
  
       <div class="clener"></div>
<div class="pattern_line margin_30-0-18-0"></div>
<h2 class="title_h40 pr"> Нови продукти <span id="pager"></span> <span class="slide-btn_box" id="next"></span> <span class="slide-btn_box" id="prev"></span> </h2>


<div id="related_products">
  <? 
  
  
 $cats  = $shop_page['content_subtype_value'];
  
   

$cats = explode(',',$cats);

$last_cat = end($cats);
//p($last_cat);

?>
  <? if(!empty($cats )) : ?>
  <? $last_cat = end($cats);

$related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 1; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 
 
 
 $related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 2; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 
 
 
 
 $related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 3; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 


//p($related_posts );
?>
  <? endif;  ?>
   <? 
 
 
 
 

 


 
   
 ?>
  
  
  
  

</div>

 <? endif; ?>