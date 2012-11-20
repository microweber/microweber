<script  type="text/javascript">
  mw.require('forms.js');

  mw.post = {
    del:function(a, callback){
      var arr = $.isArray(a) ? a : [a];
      var obj = {ids:arr}
      $.post(mw.settings.site_url + "api/delete_content", obj, function(data){
        typeof callback === 'function' ? callback.call(data) : '';
      });
    }
  }


delete_selected_posts = function(){
  var master = mwd.getElementById('pages_edit_container');
  var arr = mw.check.collectChecked(master);
  mw.post.del(arr, function(){
     mw.reload_module('#<? print $params['id'] ?>');
  });
}



 </script>

<div class="page_posts_list_tree">
  <?  if(isset($params['page-id'])):  ?>
  <?
 $pt_opts = array();

 if($params['page-id'] == 'global'){
	 $params['page-id'] = '0';
 }
 
  $pt_opts['parent'] = $params['page-id'];  
 //  $pt_opts['id'] = "pgs_tree";
 	$pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  href="#">{title}</a>';
	 $pt_opts['max_level'] = 2;
 if($params['page-id'] == '0'){
	  $pt_opts['include_first'] =  false;  
	   $pt_opts['max_level'] = 1;
 } else {
	 $pt_opts['include_first'] = 'yes';  
 }
  if(isset($params['is_shop'])){
	    $pt_opts['is_shop'] = $params['is_shop'];  
 }  
 
 
 
 
 
 
 
  //
  $pt_opts['include_categories'] = 'yes';
   
 //$pt_opts['debug'] = 2;
   if(isset($params['keyword'])){
//$pt_opts['keyword'] =$params['keyword'];
 }
 //d($pt_opts);
   pages_tree($pt_opts);
 ?>
  <? else : ?>
  <?  if(isset($params['category-id'])):  ?>
  <?
 $pt_opts = array();
  $pt_opts['parent'] = $params['category-id'];  
 //  $pt_opts['id'] = "pgs_tree";
 //	$pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  href="#">{title}</a>';

  $pt_opts['include_first'] = 'yes';
  $pt_opts['include_categories'] = 'yes';
   $pt_opts['max_level'] = 2;

   if(isset($params['keyword'])){
//$pt_opts['keyword'] =$params['keyword'];
 }
  category_tree($pt_opts);
 ?>
  <? endif; ?>
</div>
<? endif; ?>
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
 
 }
 
   if(isset($params['is_shop']) and $params['is_shop']=='y'){
	    $posts_mod['subtype'] = 'product';  
 }  
 
 
// $posts_mod['debug'] =1;
 $posts_mod['paging_param'] ='pg';
 if(isset($params['pg'])){
 $posts_mod['curent_page'] =$params['pg'];
 }
  $posts_mod['id'] = 'mw_admin_posts_manage';
if(isset($params['data-category-id'])){
        $posts_mod['category-id'] = $params['data-category-id'];
}
 $posts = array();
 

//$pics_module = is_module('pictures');
 
   $posts = module($posts_mod);
 
  
?>
<?  if(isset($posts['data']) and isarr($posts['data'])):  ?>
<div class="manage-toobar manage-toolbar-top"> <span class="mn-tb-arr-top left"></span> <span class="posts-selector left"><span onclick="mw.check.all('#pages_edit_container')">Select All</span>/<span onclick="mw.check.none('#pages_edit_container')">Unselect All</span></span> <span class="mw-ui-btn">Delete</span>
  <input value="Search for posts" type="text" class="manage-search" id="mw-search-field"  />
  <div class="post-th"> <span class="manage-ico mAuthor"></span> <span class="manage-ico mComments"></span> </div>
</div>
<div class="manage-posts-holder">
  <? foreach ($posts['data'] as $item): ?>
  <div class="manage-post-item">
    <label class="mw-ui-check left">
      <input name="select_posts_for_action" type="checkbox" value="<? print ($item['id']) ?>">
      <span></span></label>
    <?
	$pic  = get_picture(  $item['id'],  'post'); ?>
    <? if($pic == true and isset($pic['filename'])): ?>
    <a class="manage-post-image left" style="background-image: url('<? print thumbnail($pic['filename'], 108) ?>');"></a>
    <? else : ?>
    <a class="manage-post-image manage-post-image-no-image left"></a>
    <? endif; ?>
    <div class="manage-post-main">
      <h3 class="manage-post-item-title"><a href="javascript:mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');"><? print strip_tags($item['title']) ?></a></h3>
      <small><? print content_link($item['id']); ?></small>
      <div class="manage-post-item-description"> <? print character_limiter(strip_tags($item['description']), 180);
  ?> </div>
      <div class="manage-post-item-links"> <a href="<? print content_link($item['id']); ?>/editmode:y">Go live edit</a> <a href="javascript:mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');">Settings</a> <a href="javascript:;">Delete</a> </div>
    </div>
    <div class="manage-post-item-author"><? print ($item['created_by']) ?></div>
    <div class="manage-post-item-comments"><? print ($item['created_by']) ?></div>
  </div>
  <? endforeach; ?>
</div>
<div class="manage-toobar manage-toolbar-bottom"> <span class="mn-tb-arr-bottom"></span> <span class="posts-selector"> <span onclick="mw.check.all('#pages_edit_container')">Select All</span>/<span onclick="mw.check.none('#pages_edit_container')">Unselect All</span> </span> <a href="javascript:delete_selected_posts();" class="mw-ui-btn">Delete</a> </div>
<div class="mw-paging">
  <?

        $numactive = 1;
 
     if(isset($params['data-page-number'])){
                $numactive   = intval($params['data-page-number']);
              }



     if(isset($posts['paging_links']) and isarr($posts['paging_links'])):  ?>
  <? $i=1; foreach ($posts['paging_links'] as $item): ?>
  <a href="javascript:;" class="page-<? print $i; ?> <? if($numactive == $i): ?> active <? endif; ?>" onclick="mw.url.windowHashParam('<? print $posts['paging_param'] ?>','<? print $i; ?>');"><? print $i; ?></a>
  <? $i++; endforeach; ?>
  <? //d($posts['paging_links']); ?>
  <? // d($posts['paging_param']); ?>
</div>
<script  type="text/javascript">



  mw.on.moduleReload('#<? print $params['id'] ?>', function(){


    //    var page = mw.url.getHashParams(window.location.hash).pg;

       // mw.$(".mw-paging .active").removeClass("active");

       // mw.$(".mw-paging .page-"+page).addClass("active");



     });



</script>
<? endif; ?>
<? else: ?>
No posts
<? endif; ?>
