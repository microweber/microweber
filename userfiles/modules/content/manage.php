
<div class="vSpace"></div>
<div id="toggle_cats_and_pages" onmousedown="mw.switcher._switch(this, toggle_cats_and_pages);" class="mw-switcher unselectable right"><span class="mw-switch-handle"></span>
  <label>Yes
    <input type="radio" value="on" checked="checked" name="toggle_cats_and_pages" />
  </label>
  <label>No
    <input type="radio" value="off" name="toggle_cats_and_pages" />
  </label>
</div>
<label class="mw-ui-label-small right" style="margin-right: 10px;">Show Pages?</label>
<div class="mw_clear vSpace"></div>
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
     mw.reload_module('#<? print $params['id'] ?>', function(){
       toggle_cats_and_pages()
     });
  });
}




mw.manage_content_sort = function(){
  if(!mw.$("#mw_admin_posts_sortable").hasClass("ui-sortable")){
   mw.$("#mw_admin_posts_sortable").sortable({
       items: '.manage-post-item',
       axis:'y',
       handle:'.mw_admin_posts_sortable_handle',
       update:function(){
         var obj = {ids:[]}
         $(this).find('.select_posts_for_action').each(function(){
            var id = this.attributes['value'].nodeValue;
            obj.ids.push(id);
         });

         $.post("<?php print site_url('api/reorder_content'); ?>", obj, function(){});
       },
       start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
       },
       scroll:false,

       placeholder: "custom-field-main-table-placeholder"
    });

  }
}



 </script>
<div class="page_posts_list_tree" data-sortable="true" style="display:none;">
  <?  if(isset($params['page-id'])):  ?>
  <?

  
  

  
  
 $pt_opts = array();
 
  if(isset($params['is_shop'])){
	    $pt_opts['is_shop'] = $params['is_shop'];  
 }  

 if($params['page-id'] == 'global'){
	 $params['page-id'] = '0';
 } else {
	 
	 // d( $check_if_excist);
 }
 
  $pt_opts['parent'] = $params['page-id'];  
 //  $pt_opts['id'] = "pgs_tree";
 	$pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  href="{url}">{title}</a>';
	 $pt_opts['max_level'] = 2;
 if($params['page-id'] == '0'){
	  $pt_opts['include_first'] =  false;  
	   $pt_opts['max_level'] = 1;
 } else {
	 $pt_opts['include_first'] = 'yes';  
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
  <? endif; ?>
</div>
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
 
 if(isset($params['page-id']) and $params['page-id']!='global'){
  $check_if_excist = get_content_by_id($params['page-id']);
	  if(isarr($check_if_excist)){
		if(isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y'){
			$posts_mod['subtype'] = 'product';  
		}
	  }
 }
 
  if(isset($params['category-id']) and $params['category-id']!='global'){
  $check_if_excist = get_page_for_category($params['category-id']);
  
	  if(isarr($check_if_excist)){
		if(isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y'){
		 $posts_mod['subtype'] = 'product';
		}
	  }
 }
// $posts_mod['debug'] =1;
 $posts_mod['paging_param'] ='pg';
  $posts_mod['orderby'] ='created_on desc';
 
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
 
  //d($params);
?>
<?  if(isset($posts['data']) and isarr($posts['data'])):  ?>
<div class="manage-toobar manage-toolbar-top"> <span class="mn-tb-arr-top left"></span> <span class="posts-selector left"><span onclick="mw.check.all('#pages_edit_container')">Select All</span>/<span onclick="mw.check.none('#pages_edit_container')">Unselect All</span></span> <span class="mw-ui-btn">Delete</span>
  <input value="Search for posts" type="text" class="manage-search" id="mw-search-field"  />
  <div class="post-th"> <span class="manage-ico mAuthor"></span> <span class="manage-ico mComments"></span> </div>
</div>
<div class="manage-posts-holder" id="mw_admin_posts_sortable">
  <? foreach ($posts['data'] as $item): ?>
  <div class="manage-post-item">
    <label class="mw-ui-check left">
      <input name="select_posts_for_action" class="select_posts_for_action" type="checkbox" value="<? print ($item['id']) ?>">
      <span></span></label>
    <span class="ico iMove mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"></span>
    <?
	$pic  = get_picture(  $item['id'],  'post'); ?>
    <? if($pic == true and isset($pic['filename']) and trim($pic['filename']) != ''): ?>
    <a class="manage-post-image left" style="background-image: url('<? print thumbnail($pic['filename'], 108) ?>');"></a>
    <? else : ?>
    <a class="manage-post-image manage-post-image-no-image left"></a>
    <? endif; ?>
    <div class="manage-post-main">
      <h3 class="manage-post-item-title"><a href="javascript:mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');"><? print strip_tags($item['title']) ?></a></h3>
      <small><? print content_link($item['id']); ?></small>
      <div class="manage-post-item-description"> <? print character_limiter(strip_tags($item['description']), 60);
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
<? $cat_name = '';
if( isset($params['category-id']) and intval($params['category-id']) > 0){
	$cat = get('categories/'.$params['category-id']);
	d($cat);
}
 ?>
<div class="mw-no-posts-foot">
  <? if( isset($posts_mod['subtype']) and $posts_mod['subtype'] == 'product') : ?>
  <h2>No Products Here</h2>
  
  <a href="#?action=new:product" class="mw-ui-btn-rect"><span class="ico iplus"></span><span class="ico iproduct"></span>Add New Product in <b id="tttt"><script>$('#tttt').html($('.item_97 > a span:first').text());</script></b></a>
  <? else: ?>
  <h2>No Posts Here</h2>
  <a href="#?action=new:post" class="mw-ui-btn-rect"><span class="ico iplus"></span><span class="ico ipost"></span>Create New Post </a> </div>
<? endif; ?>
<? endif; ?>

