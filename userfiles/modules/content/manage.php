<?php

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
$posts_mod['orderby'] ='position desc';

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
<?php  if(isset($params['page-id'])):  ?>
<?php

if($params['page-id'] == 'global'){
 if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
   $page_info = get_content('limit=1&one=1&content_type=page&is_shop=y');
 }
 else {
   $page_info = get_homepage();
 }

}
else {
 $page_info = get_content_by_id($params['page-id']);
}

?>


<script type="text/javascript">





$(document).ready(function(){


		var prev_frame_attrs = {};

$('#mw_page_layout_preview').attr('data-page-id',"<?php print ($page_info['id'])?>");
$('#mw_page_layout_preview').attr('inherit_from',"<?php print ($page_info['id'])?>");
$('#mw_page_layout_preview').attr('edit_page_id',"<?php print ($page_info['id'])?>");

$('#mw_page_layout_preview').attr('autoload',"1");

$('#mw_page_layout_preview').attr('data-small',"1");


 mw.load_module("content/layout_selector", '#mw_page_layout_preview', false);





    });
</script>




<div class="mw-admin-page-preview-holder">
  <div  class="mw-admin-page-preview-page">
    <div style="width: 370px;margin-left: 30px;" class="left">
      <?php if(isarr($page_info) and isset($page_info['title'])): ?>
      <?php if($page_info['is_shop'] == 'y'){ $type='shop'; } elseif($page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>
      <h2 class="hr" style="padding-top: 19px;"><span class="icotype icotype-<?php print $type; ?>"></span><?php print ($page_info['title']) ?></h2>
      <?php endif; ?>
      <div style="height:335px;" id="mw_page_layout_preview"></div>
    </div>
    <div class="right" style="width: 210px;">
      <?php if(isset($page_info) and isset($page_info['title'])): ?>
      <?php  /*   <ul class="mw-quick-links mw-quick-links-blue">
    <li>


        <a href="<?php print page_link($params["page-id"]);  ?>/editmode:y">
            <span class="ico ilive"></span>
            <span>Go Live Edit</span>
        </a>
    </li>
  </ul>  */  ?>
      <?php endif; ?>
      <h2 class="hr" style="padding-top: 19px;"><?php _e("Add New"); ?></h2>
      <ul class="mw-quick-links mw-quick-links-green" >
        <li> <a href="#action=new:page&parent_page=<?php print $params["page-id"]; ?>"> <span class="mw-ui-btn-plus">&nbsp;</span> <span class="ico ipage"></span> <span><?php _e("New Page"); ?></span> </a> </li>
        <li> <a href="#action=new:category&parent_page=<?php print $params["page-id"]; ?>"> <span class="mw-ui-btn-plus">&nbsp;</span> <span class="ico icategory"></span> <span><?php _e("New Category"); ?></span> </a> </li>
        <?php if(isset($params['is_shop']) and $params['is_shop'] == 'y'): ?>
        <?php else :  ?>
        <li> <a href="#action=new:post&parent_page=<?php print $params["page-id"]; ?>"> <span class="mw-ui-btn-plus">&nbsp;</span> <span class="ico ipost"></span> <span><?php _e("New Post"); ?></span> </a> </li>
        <?php endif; ?>
        <li> <a href="#action=new:product&parent_page=<?php print $params["page-id"]; ?>"> <span class="mw-ui-btn-plus">&nbsp;</span> <span class="ico iproduct"></span> <span><?php _e("New Product"); ?></span> </a> </li>
      </ul>
    </div>
    <div class="mw_clear"></div>
  </div>
  <div class="mw-admin-page-preview-page-info"> </div>
</div>
<?php elseif(isset($params["data-category-id"])):?>
<?php  $cat_info = get_category_by_id($params["data-category-id"]);
//d($cat_info );
?>
    <?php if(isset($cat_info['title']) and $cat_info['title'] != ''): ?>
      <div style="width: 370px;margin-left: 30px;" class="left">
<h2   style="padding-top: 16px;"><span class="ico icategory"></span>&nbsp;<?php _e("Category"); ?> <small style="opacity:0.4">&raquo;</small> <?php print ($cat_info ['title']) ?></h2>
</div>
<?php endif; ?>
<?php endif; ?>
<div class="right" style="overflow: hidden;padding-bottom: 25px;padding-top: 10px;padding-left: 30px">
  <div id="toggle_cats_and_pages" onmousedown="mw.switcher._switch(this, toggle_cats_and_pages);" class="mw-switcher unselectable right"><span class="mw-switch-handle"></span>
    <label><?php _e("Yes"); ?>
      <input type="radio" value="on" checked="checked" name="toggle_cats_and_pages" />
    </label>
    <label><?php _e("No"); ?>
      <input type="radio" value="off" name="toggle_cats_and_pages" />
    </label>
  </div>
  <label class="mw-ui-label-small right" style="margin-right: 10px;"><?php _e("Show Pages"); ?>?</label>
</div>
<?php if(isset($page_info) and isset($page_info['title'])): ?>
<?php if($page_info['is_shop'] == 'y'){ ?>
<h2 class="left" style="padding-left: 20px;width: 430px;"><?php _e("Products from"); ?> <?php print ($page_info['title']) ?></h2>
<?php } else{  ?>
<h2 class="left" style="padding-left: 20px;width: 430px;"><?php _e("Posts from"); ?> <?php print ($page_info['title']) ?></h2>
<?php } ?>
<?php endif; ?>
<div class="mw_clear"></div>
<script  type="text/javascript">
mw.require('forms.js', true);

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


mw.tools.confirm("<?php _e("Are you sure you want to delete the selected posts"); ?>?", function(){
  var master = mwd.getElementById('pages_edit_container');
  var arr = mw.check.collectChecked(master);
  mw.post.del(arr, function(){
   mw.reload_module('#<?php print $params['id'] ?>', function(){
     toggle_cats_and_pages()
   });
 });
});

}


mw.delete_single_post = function(id){
   mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function(){
      var arr = id;
       mw.post.del(arr, function(){
        mw.$(".manage-post-item-"+id).fadeOut();
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

       //placeholder: "custom-field-main-table-placeholder",
       scroll:false


     });

 }
}



</script>
<div class="page_posts_list_tree" data-sortable="true" style="display:none;">

  <?php  if(isset($params['page-id'])):  ?>
  <?php






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
$pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  data-type="{content_type}"   data-shop="{is_shop}"  subtype="{subtype}" href="{url}">{title}</a>';
$pt_opts['max_level'] = 2;
if($params['page-id'] == '0'){
 $pt_opts['include_first'] =  false;
 $pt_opts['max_level'] = 1;
} else {
  $pt_opts['include_first'] = 'yes';

}






  //
$pt_opts['include_categories'] = 'yes';

// $pt_opts['debug'] = 2;
if(isset($params['keyword'])){
//$pt_opts['keyword'] =$params['keyword'];
}

pages_tree($pt_opts);
?>
  <?php else : ?>

  <?php  if(isset($params['category-id'])):  ?>
  <?php
$pt_opts = array();
$pt_opts['parent'] = $params['category-id'];
 //  $pt_opts['id'] = "pgs_tree";
 //	$pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  href="#">{title}</a>';

//$pt_opts['include_first'] = 'yes';
//$pt_opts['include_categories'] = 'yes';
$pt_opts['max_level'] = 2;

if(isset($params['keyword'])){
//$pt_opts['keyword'] =$params['keyword'];
}

category_tree($pt_opts);
?>
  <?php endif; ?>
  <?php endif; ?>
</div>
<div class="manage-toobar manage-toolbar-top"> <span class="mn-tb-arr-top left"></span> <span class="posts-selector left"><span onclick="mw.check.all('#mw_admin_posts_manage')"><?php _e("Select All"); ?></span>/<span onclick="mw.check.none('#mw_admin_posts_manage')"><?php _e("Unselect All"); ?></span></span> <span class="mw-ui-btn" onclick="delete_selected_posts();"><?php _e("Delete"); ?></span>
  <input
  onfocus="mw.form.dstatic(event);"
  onblur="mw.form.dstatic(event);"
  onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('search',this.value)})"
  value="<?php  if(isset($params['keyword']) and $params['keyword'] != false):  ?><?php print $params['keyword'] ?><?php else: ?><?php _e("Search for posts"); ?><?php endif; ?>"
  data-default="<?php _e("Search for posts"); ?>"
  type="text"
  class="manage-search"
  id="mw-search-field"   />
  <div class="post-th"> <span class="manage-ico mAuthor"></span> <span class="manage-ico mComments"></span> </div>
</div>
<?php    print $posts = module( $posts_mod);  ?>

<script  type="text/javascript">

//paging
mw.on.hashParam("pg", function(){

 var dis = $p_id = this;

 mw.$('#mw_admin_posts_manage').attr("paging_param", 'pg');


 if(dis!==''){
   mw.$('#mw_admin_posts_manage').attr("pg", dis);
   mw.$('#mw_admin_posts_manage').attr("data-page-number", dis);
 }



     $p_id = $(this).attr('data-page-number');
     $p_param = $(this).attr('data-paging-param');
     mw.$('#mw_admin_posts_manage').attr('data-page-number',$p_id);
     mw.$('#mw_admin_posts_manage').attr('data-page-param',$p_param);
     mw.$('#mw_admin_posts_manage').removeAttr('data-content-id');

    mw.reload_module('#mw_admin_posts_manage');



  });




mw.on.hashParam("search", function(){

 mw.$('#mw_admin_posts_manage').attr("data-type",'content/admin_posts_list');

 var dis = this;
 if(dis!==''){
   mw.$('#mw_admin_posts_manage').attr("data-keyword", dis);
   mw.url.windowDeleteHashParam('<?php print $posts_mod['paging_param'] ?>')
	   mw.$('#mw_admin_posts_manage').attr("data-page-number", 1);
    }
   else{
    mw.$('#mw_admin_posts_manage').removeAttr("data-keyword");
    mw.url.windowDeleteHashParam('search')
  }
  mw.reload_module('#mw_admin_posts_manage');
});

mw.on.moduleReload('#<?php print $params['id'] ?>', function(){   });








</script>
<?php





