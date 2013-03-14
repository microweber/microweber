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
$data = get_content($comments_data);
?>
<? if(isarr($data )): ?>


<div>
  <? foreach($data  as $item){ ?>
         
    <module type="comments/comments_for_post" id="mw_comments_for_post_<? print $item['id'] ?>" content_id="<? print $item['id'] ?>" >

  <?php // _d($item);  break;  ?>
  <? } ; ?>
</div>
<? endif; ?>
