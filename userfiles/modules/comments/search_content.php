<?
	$comments_data = array();
$comments_data['in_table'] =  'table_comments';
$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
	$comments_data['keyword'] =  $params['search-keyword'];
	//	$comments_data['debug'] =  'comments/global';
}
$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>

<ul>
 <li><a href="#comments_for_content=0">Show all</a></li>
  <? foreach($data  as $item): ?>
  <li><a href="#comments_for_content=<? print $item['id'] ?>"><? print $item['title'] ?></a></li>
  <? endforeach ; ?>
</ul>
<? endif; ?>
