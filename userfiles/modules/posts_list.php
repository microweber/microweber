<? 

// d($params);
 
$post_params = $params; 

if(isset($post_params['id'])){
	$paging_param = 'curent_page'.crc32($post_params['id']);
unset($post_params['id']);	
} else {
$paging_param = 'curent_page';	
}



if(isset($post_params['data-page-number'])){
	   
	$post_params['curent_page'] = $post_params['data-page-number'];
unset($post_params['data-page-number']);	
}  





if(isset($params['data-paging-param'])){
	
	 $paging_param = $params['data-paging-param'];	
//	d($paging_param);
}







?>
<? 

if(isset($post_params['data-page-id'])){
 $post_params['parent'] = intval($post_params['data-page-id']);
}


//$post_params['debug'] = 'posts';
$post_params['content_type'] = 'post';
$content = get_content($post_params);
 
  ?>
<? 

$post_params_paging = $post_params;
//$post_params_paging['count'] = true; 




$post_params_paging['page_count'] = true; 
$pages = get_content($post_params_paging);


 $pages_count = intval($pages );
  ?>

<hr>
<?  if(intval($pages) > 1): ?>
<? $paging_links = paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<? if(!empty($paging_links)): ?>
<div class="paging">
  <? foreach($paging_links as $k => $v): ?>
  <span class="paging-item" data-page-number="<? print $k; ?>" ><a  data-page-number="<? print $k; ?>" data-paging-param="<? print $paging_param; ?>" href="<? print $v; ?>"  class="paging-link"><? print $k; ?></a></span>
  <? endforeach; ?>
</div>
<? endif; ?>
<? endif; ?>
<hr>
<? if(!empty($content)): ?>
<div class="content-list">
  <? foreach($content as $item): ?>
  <div class="content-item" data-content-id="<? print ($item['id']) ?>"> id:<? print ($item['id']) ?> / <a  href="<? print post_link($item['id']) ?>"><? print post_link($item['id']) ?></a><br>
    <? print $item['title'] ?> </div>
  <? endforeach; ?>
</div>
<? endif; ?>
<textarea>data-type="posts" 
data-fields="id,thumbnail, title, description, date, categories, author, comments, price, expriration_date" 
data-read-more='aaaa' 
data-thumbnail-size='300x200' 
data-category='10, shop' 
data-posts-per-page=10 
data-order="date,asc"
data-keyword="ivan"

data-callback="mw.posts.fancy('#aadas')"
data-list-tag="ul, table"


data-filter-price="<60"
data-filter-price=">30"
data-filter-title="^ivan*"
data-filter-date="<> 1 hour ago</textarea>
