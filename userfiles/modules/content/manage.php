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
  $posts_mod['orderby'] ='position asc';

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
<?  //d($params['page-id']); ?>
<?  if(isset($params['page-id'])):  ?>
 <?

if($params['page-id'] == 'global'){
 if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
 $page_info = get_content('limit=1&one=1&content_type=page&is_shop=y');
//d( $page_info1 );
} else {
 $page_info = get_homepage();


}

} else {


 $page_info = get_content_by_id($params['page-id']);
}

 ?>



<div class="mw-admin-page-preview-holder">

<div  class="mw-admin-page-preview-page">


<div style="width: 370px;margin-left: 30px;" class="left">
    <? if(isarr($page_info) and isset($page_info['title'])): ?>


      <?php if($page_info['is_shop'] == 'y'){ $type='shop'; } elseif($page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>


  <h2 class="hr" style="padding-top: 19px;"><span class="icotype icotype-<?php print $type; ?>"></span><? print ($page_info['title']) ?></h2>

  <?php endif; ?>

  <module data-type="content/layout_selector" data-page-id="<? print ($page_info['id'])?>" autoload="1" inherit_from="<? print ($page_info['id'])?>" data-small=1 edit_page_id="<? print ($page_info['id'])?>"  />



  </div>





<div class="right" style="width: 210px;">


 <? if(isset($page_info) and isset($page_info['title'])): ?>



  <?php  /*   <ul class="mw-quick-links mw-quick-links-blue">
    <li>


        <a href="<?php print page_link($params["page-id"]);  ?>/editmode:y">
            <span class="ico ilive"></span>
            <span>Go Live Edit</span>
        </a>
    </li>
  </ul>  */  ?>


  <? endif; ?>

    <h2 class="hr" style="padding-top: 19px;">Add new</h2>
    <ul class="mw-quick-links mw-quick-links-green" >
      <li>
        <a href="#action=new:page&parent_page=<? print $params["page-id"]; ?>">
            <span class="mw-ui-btn-plus">&nbsp;</span>
            <span class="ico ipage"></span>
            <span>New Page</span>
        </a>
      </li>
      <li>
        <a href="#action=new:category&parent_page=<? print $params["page-id"]; ?>">
            <span class="mw-ui-btn-plus">&nbsp;</span>
            <span class="ico icategory"></span>
            <span>New Category</span>
        </a>
      </li>
      <li>
        <a href="#action=new:post&parent_page=<? print $params["page-id"]; ?>">
            <span class="mw-ui-btn-plus">&nbsp;</span>
            <span class="ico ipost"></span>
            <span>New Post</span>
        </a>
      </li>
      <li>
        <a href="#action=new:product&parent_page=<? print $params["page-id"]; ?>">
            <span class="mw-ui-btn-plus">&nbsp;</span>
            <span class="ico iproduct"></span>
            <span>New Product</span>
        </a>
      </li>
    </ul>


</div>

<div class="mw_clear"></div>

</div>

<div class="mw-admin-page-preview-page-info">


</div>
</div>


<? endif; ?>


<div class="right" style="overflow: hidden;padding-bottom: 25px;padding-top: 10px;padding-left: 30px">
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



<? if(isset($page_info) and isset($page_info['title'])): ?>

<?php if($page_info['is_shop'] == 'y'){ ?>
<h2 class="left" style="padding-left: 20px;width: 430px;">Products from <? print ($page_info['title']) ?></h2>
<?php } else{  ?>

<h2 class="left" style="padding-left: 20px;width: 430px;">Posts from <? print ($page_info['title']) ?></h2>


<?php } ?>

<?php endif; ?>

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


mw.delete_single_post = function(id){
	 var r=confirm("Do you want to delete this post?")
	if (r==true) {
		 var arr = id;
		  mw.post.del(arr, function(){
			 mw.$(".manage-post-item-"+id).fadeOut();
		  });
	   //return false;
	  }	else {
		//return false;
	  }

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
<div class="manage-toobar manage-toolbar-top"> <span class="mn-tb-arr-top left"></span> <span class="posts-selector left"><span onclick="mw.check.all('#mw_admin_posts_manage')">Select All</span>/<span onclick="mw.check.none('#mw_admin_posts_manage')">Unselect All</span></span> <span class="mw-ui-btn" onclick="delete_selected_posts();">Delete</span>
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

