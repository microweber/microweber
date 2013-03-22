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
$comments_data['to_table'] =  'table_content';
$comments_data['to_table_id'] =  $params['content_id'];

} else {
	
	if(isset($params['to_table'])){
	$comments_data['to_table'] =  $params['to_table'];
	}
	
	if(isset($params['to_table_id'])){
	$comments_data['to_table_id'] =  $params['to_table_id'];
	}
	
}
//$comments_data['in_table'] =  'table_comments';
//$comments_data['cache_group'] =  'comments/global';
if(isset($params['search-keyword'])){
$comments_data['keyword'] =  $params['search-keyword'];
}
$comments_data['group_by'] =  'to_table,to_table_id';
 
$data = get_comments($comments_data);

 

//$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>


<div>
  <? foreach($data  as $item){ ?>
     <? if(isset($item['to_table']) and $item['to_table'] == 'table_content'): ?>
    <module type="comments/comments_for_post" id="mw_comments_for_post_<? print $item['to_table_id'] ?>" content_id="<? print $item['to_table_id'] ?>" >
     <? endif; ?>
     
     
      <? if(isset($item['to_table']) and $item['to_table'] == 'table_modules'): ?>
    <module type="comments/comments_for_module" id="mw_comments_for_module_<? print $item['to_table_id'] ?>" to_table_id="<? print $item['to_table_id'] ?>" to_table="<? print $item['to_table'] ?>" >
     <? endif; ?>
     
     
     
  <?php // _d($item);  break;  ?>
  <? } ; ?>
</div>
<? endif; ?>
