<?
only_admin_access() ;
$comments_data = array();
$comments_data['in_table'] =  'table_comments';
$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
$comments_data['keyword'] =  $params['search-keyword'];
}
if(isset($params['content_id'])){
$comments_data['id'] =  $params['content_id'];
}

$comments_data = array();
if(isset($params['content_id'])){
$comments_data['rel'] =  'content';
$comments_data['rel_id'] =  $params['content_id'];

} else {
	
	if(isset($params['rel'])){
	$comments_data['rel'] =  $params['rel'];
	}
	
	if(isset($params['rel_id'])){
	$comments_data['rel_id'] =  $params['rel_id'];
	}
	
}
//$comments_data['in_table'] =  'table_comments';
//$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
$comments_data['keyword'] =  $params['search-keyword'];
$comments_data['search_in_fields'] =  'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';

 
}
$comments_data['group_by'] =  'rel,rel_id';
 //$comments_data['debug'] =  'rel,rel_id';
$data = get_comments($comments_data);

 

//$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>


<div class="mw-admin-comments-search-holder">
  <? foreach($data  as $item){ ?>
     <? if(isset($item['rel']) and $item['rel'] == 'content'): ?>
    <module type="comments/comments_for_post" id="mw_comments_for_post_<? print $item['rel_id'] ?>" content_id="<? print $item['rel_id'] ?>" >
     <? endif; ?>
     
     
      <? if(isset($item['rel']) and $item['rel'] == 'table_modules'): ?>
    <module type="comments/comments_for_module" id="mw_comments_for_post_<? print $item['rel_id'] ?>" rel_id="<? print $item['rel_id'] ?>" rel="<? print $item['rel'] ?>" >
     <? endif; ?>
     
     
     
  <?php // _d($item);  break;  ?>
  <? } ; ?>
</div>
<? endif; ?>
