<?
$posts_mod = array();
$posts_mod['type'] = 'content/admin_posts_list';
 // $posts_mod['display'] = 'custom';
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
 
 if(!isset($params['category-id']) and isset($params['page-id']) and $params['page-id']!='global'){
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
  $posts_mod['wrap'] =1;
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
 
 
//print $posts ;
  //d($params);
?>
<? //d($params); ?>

<div class="mw-manageconten-nav">
  <? if(isset($posts_mod['subtype']) and $posts_mod['subtype'] == 'product'): ?>
  <? if(isset($params["selected-category-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-product-add" href="#action=new:product&category_id=<? print $params["selected-category-id"]; ?>"><span></span><strong>New Product</strong></a>
  <? elseif(isset($params["page-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-product-add" href="#action=new:product&parent_page=<? print $params["page-id"]; ?>"><span></span><strong>New Product</strong></a>
  <? endif; ?>
  <? else: ?>
  <? if(isset($params["selected-category-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-post-add" href="#action=new:post&category_id=<? print $params["selected-category-id"]; ?>"><span></span><strong>New Post</strong></a>
  <? elseif(isset($params["page-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-post-add" href="#action=new:post&parent_page=<? print $params["page-id"]; ?>"><span></span><strong>New Post</strong></a>
  <? endif; ?>
  <? endif; ?>
  <? if(isset($params["selected-category-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-category-add" href="#action=new:category&parent_id=<? print $params["selected-category-id"]; ?>"><span></span><strong>Sub Category</strong></a> <a class="mw-manage-btn mw-manage-btn-category-edit" href="#action=editcategory:<? print $params["selected-category-id"]; ?>"><span></span><strong>Edit Category</strong></a>
  <? elseif(isset($params["page-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-category-add" href="#action=new:category&parent_page=<? print $params["page-id"]; ?>"><span></span><strong>New Category</strong></a>
  <? endif; ?>
  <? if(isset($params["page-id"])): ?>
  <a class="mw-manage-btn mw-manage-btn-page-edit" href="#action=editpage:<? print $params["page-id"]; ?>"><span></span><strong>Edit Page</strong></a>
  <? endif; ?>
</div>
<div style="overflow: hidden;padding-bottom: 25px;padding-top: 10px;">
  <div id="toggle_cats_and_pages" onmousedown="mw.switcher._switch(this, toggle_cats_and_pages);" class="mw-switcher unselectable right"><span class="mw-switch-handle"></span>
    <label>Yes
      <input type="radio" value="on" checked="checked" name="toggle_cats_and_pages" />
    </label>
    <label>No
      <input type="radio" value="off" name="toggle_cats_and_pages" />
    </label>
  </div>
  <label class="mw-ui-label-small right" style="margin-right: 10px;">Show Pages?</label>
</div>
<div class="mw_clear"></div>
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
<div class="manage-toobar manage-toolbar-top"> <span class="mn-tb-arr-top left"></span> <span class="posts-selector left"><span onclick="mw.check.all('#mw_admin_posts_manage')">Select All</span>/<span onclick="mw.check.none('#mw_admin_posts_manage')">Unselect All</span></span> <span class="mw-ui-btn">Delete</span>
  <input
            onfocus="mw.form.dstatic(event);"
            onblur="mw.form.dstatic(event);"
             onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('search',this.value)})"
            value="<?  if(isset($params['keyword']) and $params['keyword'] != false):  ?><? print $params['keyword'] ?><? else: ?><?php _e("Search for posts"); ?><? endif; ?>"
            data-default="<?php _e("Search for posts"); ?>"
            type="text"
            class="manage-search"
            id="mw-search-field"   />
  <div class="post-th"> <span class="manage-ico mAuthor"></span> <span class="manage-ico mComments"></span> </div>
</div>
<?    print $posts = module( $posts_mod);  ?>
</div>
<script  type="text/javascript">

//paging
  mw.on.hashParam("pg", function(){

     var dis =  $p_id = this;

 mw.$('#mw_admin_posts_manage').attr("paging_param", 'pg');


     if(dis!==''){
       mw.$('#mw_admin_posts_manage').attr("pg", dis);
        mw.$('#mw_admin_posts_manage').attr("data-page-number", dis);
     }
     else{
       // mw.$('#mw_admin_posts_manage').removeAttr("pg");
      //  mw.$('#mw_admin_posts_manage').removeAttr("data-page-number");
       // mw.url.windowDeleteHashParam('pg');
     }


 $p_id = $(this).attr('data-page-number');
	 $p_param = $(this).attr('data-paging-param');
	 mw.$('#mw_admin_posts_manage').attr('data-page-number',$p_id);
	 mw.$('#mw_admin_posts_manage').attr('data-page-param',$p_param);
 mw.$('#mw_admin_posts_manage').removeAttr('data-content-id');


  	// mw.load_module('content/admin_posts_list','#mw_admin_posts_manage');

  mw.reload_module('#mw_admin_posts_manage');
 


 });
 
 
 

 mw.on.hashParam("search", function(){

 mw.$('#mw_admin_posts_manage').attr("data-type",'content/admin_posts_list');

   var dis = this;
   if(dis!==''){
     mw.$('#mw_admin_posts_manage').attr("data-keyword", dis);
	  mw.url.windowDeleteHashParam('<? print $posts_mod['paging_param'] ?>')
	//   mw.$('#mw_admin_posts_manage').removeAttr("<? print $posts_mod['paging_param'] ?>");
	    mw.$('#mw_admin_posts_manage').attr("data-page-number", 1);
			//    mw.$('#mw_admin_posts_manage').attr("data-page-number", 1);

   }
   else{
      mw.$('#mw_admin_posts_manage').removeAttr("data-keyword");
      mw.url.windowDeleteHashParam('search')
   }
   mw.reload_module('#mw_admin_posts_manage');
 });

  mw.on.moduleReload('#<? print $params['id'] ?>', function(){


    //    var page = mw.url.getHashParams(window.location.hash).pg;

       // mw.$(".mw-paging .active").removeClass("active");

       // mw.$(".mw-paging .page-"+page).addClass("active");



     });



</script>
<? /*$cat_name = '';
if( isset($params['category-id']) and intval($params['category-id']) > 0){
	$cat = get('limit=1&table=categories&id='.$params['category-id']);
	if(isarr($cat[0])){
		 $cat_name = ' in '.$cat[0]['title'];
	}
//	d($cat);
}
 
*/
