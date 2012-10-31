<?  if(isset($params['page-id'])):  ?>

<h2>Pages</h2>
<?
 $pt_opts = array();
  $pt_opts['parent'] = $params['page-id'];  
 //  $pt_opts['id'] = "manage_posts_pagings_tree";
  $pt_opts['include_first'] = 'yes';  
  $pt_opts['include_categories'] = 'yes';
   if(isset($params['keyword'])){ 
//$pt_opts['keyword'] =$params['keyword'];
 }
   pages_tree($pt_opts);
 ?>
<? endif; ?>
<? //d($params); ?>
<?
$posts_mod = array();
$posts_mod['type'] = 'posts_list';
 $posts_mod['display'] = 'custom';
 if(isset($params['page-id'])){ 
$posts_mod['data-page-id'] =$params['page-id'];
 }
 
  if(isset($params['keyword'])){ 
 $posts_mod['search_by_keyword'] =$params['keyword'];
 }
   if(isset($params['keyword'])){ 
//$posts_mod['debug'] =1;
 }
 $posts_mod['paging_param'] ='manage_posts_paging';
 if(isset($params['manage_posts_paging'])){ 
 $posts_mod['curent_page'] =$params['manage_posts_paging'];
 }
  
  if(isset($params['data-category-id'])){ 
$posts_mod['data-category-id'] =$params['data-category-id'];
 }
 $posts = array();
//
  $posts = module($posts_mod);
  //print $posts ;  
?>
<h2>Posts</h2>
<?  if(isset($posts['data']) and isarr($posts['data'])):  ?>
<div class="manage-posts-holder">
  <? foreach ($posts['data'] as $item): ?>
  <div class="manage-post-item">
    <input name="select_posts_for_action" type="checkbox" value="<? print ($item['id']) ?>">
    <h3 class="manage-post-item-title"><? print ($item['title']) ?></h3>
    <small><? print content_link($item['id']); ?></small>
    <div class="manage-post-item-description" ><? print strip_tags($item['description']) ?></div>
    <div class="manage-post-item-author" ><? print ($item['created_by']) ?></div>
    <div class="manage-post-item-comments" ><? print ($item['created_by']) ?></div>
    <div class="manage-post-item-links" > <a href="<? print content_link($item['id']); ?>/editmode:y">View</a> <a href="javascript:mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');">Edit</a> <a href="javascript:mw.url.windowHashParam('action','deletepost:<? print ($item['id']) ?>');">Delete</a> </div>
  </div>
  <? endforeach; ?>
  <?  if(isset($posts['paging_links']) and isarr($posts['paging_links'])):  ?>
  <? $i=1; foreach ($posts['paging_links'] as $item): ?>
  <a href="javascript:mw.url.windowHashParam('<? print $posts['paging_param'] ?>','<? print $i; ?>');"><? print $i; ?></a>
  <? $i++; endforeach; ?>
  <? //d($posts['paging_links']); ?>
  <? // d($posts['paging_param']); ?>
  
  
  
  
  <script  type="text/javascript">
   mw.on.hashParam("<? print $posts['paging_param'] ?>", function(){

 //mw.$('#pages_edit_container').attr("data-type",'content/manage');

   var dis = this.trim();
   if(dis!==''){
     mw.$('#pages_edit_container').attr("<? print $posts['paging_param'] ?>", dis);
   }
   else{
      mw.$('#pages_edit_container').removeAttr("<? print $posts['paging_param'] ?>");
      mw.url.windowDeleteHashParam('<? print $posts['paging_param'] ?>')
   }
   mw.reload_module('#<? print $params['id'] ?>');
 });
</script>
  
  
  
  <? endif; ?>
  
</div>
<? else: ?>
No posts
<? endif; ?>
