<?php
only_admin_access() ;
$comments_data = array();
$comments_data['in_table'] =  'comments';
$comments_data['cache_group'] =  'comments/main';
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
//$comments_data['in_table'] =  'comments';
//$comments_data['cache_group'] =  'comments/main';
if(isset($params['search-keyword'])){
$comments_data['keyword'] =  $params['search-keyword'];
$comments_data['search_in_fields'] =  'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';

 
}
$comments_data['group_by'] =  'rel_id,rel';
$comments_data['order_by'] =  'created_on desc';

//  $comments_data['debug'] =  'rel,rel_id';
$data = get_comments($comments_data);
 

//$data = get_content($comments_data);
?>
<?php if(is_array($data )): ?>

<div class="mw-admin-comments-search-holder">
	<?php foreach($data  as $item){ ?>
	<?php if(isset($item['rel']) and $item['rel'] == 'content'): ?>
	<module type="comments/comments_for_post" id="mw_comments_for_post_<?php print $item['rel_id'] ?>" content_id="<?php print $item['rel_id'] ?>" >
	<?php endif; ?>
	<?php if(isset($item['rel']) and $item['rel'] == 'modules'): ?>
	<module type="comments/comments_for_module" id="mw_comments_for_post_<?php print $item['rel_id'] ?>" rel_id="<?php print $item['rel_id'] ?>" rel="<?php print $item['rel'] ?>" >
	<?php endif; ?>
	<?php // _d($item);  break;  ?>
	<?php } ; ?>
</div>
<?php else: ?>
<h5>There are not comments here.</h5>
<br />
<a href="#content_id=0" class="mw-ui-btn">
<?php _e("See all comments"); ?>
</a>
<?php endif; ?>
