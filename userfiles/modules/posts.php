<? 

d($params);


$post_params = $params; 

if($post_params['id']){
	
unset($post_params['id']);	
}

?>
<? $content = get_content($post_params); 

 
 ?>
<? if(!empty($content)): ?>
<? foreach($content as $item): ?>

<p> id:<? print ($item['id']) ?> / <? print post_link($item['id']) ?><br>
  <? print $item['content_title'] ?></p>
<? endforeach; ?>
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
