<?php
only_admin_access();
$action = url_param('action');
$is_in_shop = false;
$rand = uniqid(); ?>
<?php $my_tree_id = crc32(mw('url')->string()); ?>
<?php $active_content_id = '';
if(isset($_REQUEST['edit_content']) and $_REQUEST['edit_content'] != 0){
	$active_content_id = $_REQUEST['edit_content'];
}

 ?>
<script type="text/javascript">






  if(typeof toggle_cats_and_pages === 'undefined'){
              toggle_cats_and_pages = function(){
                  mw.cookie.ui('ToggleCatsAndPages', this.value);
                  _toggle_cats_and_pages();
              }
              _toggle_cats_and_pages = function(callback){
                  var state =  mw.cookie.ui('ToggleCatsAndPages');
                  if(state == 'on'){
                       mw.$(".page_posts_list_tree").show();
                  }
                  else{
                      mw.$(".page_posts_list_tree").hide();
                  }
                  typeof callback === 'function' ? callback.call(state) : '';
              }
    }


              $(document).ready(function(){
                _toggle_cats_and_pages(function(){
                  if(this=='on'){
                    mw.switcher.on(mwd.getElementById('toggle_cats_and_pages'));
                  }
                  else{
                    mw.switcher.off(mwd.getElementById('toggle_cats_and_pages'));
                  }
                });
              });
              $(document.body).ajaxStop(function(){
                _toggle_cats_and_pages(function(){
                  if(this=='on'){
                    mw.switcher.on(mwd.getElementById('toggle_cats_and_pages'));
                  }
                  else{
                    mw.switcher.off(mwd.getElementById('toggle_cats_and_pages'));
                  }
                });
              });


            </script>
<script type="text/javascript">



<?php   include_once( MW_INCLUDES_DIR . 'api/treerenderer.php');
 ?>



$(document).ready(function(){
    mw.treeRenderer.appendUI();
    mw.treeRenderer.appendUI('.page_posts_list_tree');
    mw.on.hashParam("page-posts", function(){
        mw_set_edit_posts(this);
    });
    mw.on.moduleReload("pages_tree_toolbar", function(e){
        mw.treeRenderer.appendUI();
		 mw_make_pages_tree_sortable()
    });
    mw.on.moduleReload("pages_edit_container", function(){
        mw.treeRenderer.appendUI("#pages_edit_container .page_posts_list_tree");
    });
   $(mwd.body).ajaxStop(function(){
      $(this).removeClass("loading");
   });
   
   
    
   mw_make_pages_tree_sortable();


   
});




mw.contentAction = {
  manage:function(type, id){
      var id = id || 0;
      if(type==='page'){
        mw_select_page_for_editing(id);
      }
      else if(type==='post'){
        mw_select_post_for_editing(id);
      }
      else if(type==='category'){
        mw_select_category_for_editing(id);
      }
      else if(type==='product'){
        mw_add_product(id);
      }
      mw.$(".mw_action_nav").addClass("not-active");
      mw.$(".mw_action_"+type).removeClass("not-active");
  },
  create:function(a){
    return mw.contentAction.manage(a, 0);
  }
}
function mw_delete_content($p_id){
	 mw.$('#pages_edit_container').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#pages_edit_container');
}

function mw_select_page_for_editing($p_id){
  	 var  active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
     var active_item_is_page  = $p_id;
     var  active_item_is_parent = mw.url.windowHashParam("parent-page");
     var active_item_is_category = active_item.attr('data-category-id');
	 
	  mw.$('.mw-admin-go-live-now-btn').attr('content-id',active_item_is_parent);
	 
	 
	 

	 if(active_item_is_category != undefined){
        mw.$('#pages_edit_container').attr('data-parent-category-id',active_item_is_category);
        var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.have_category').first();
        if(active_item_parent_page != undefined){
            var active_item_is_page = active_item_parent_page.attr('data-page-id');
        }
        else {
            var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.is_page').first();
            if(active_item_parent_page != undefined){
                var active_item_is_page = active_item_parent_page.attr('data-page-id');
            }
        }
	 }
     else {
	    mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
	 }
	 if(active_item_is_parent != undefined){
	     mw.$(".pages_tree_item.active-bg").children().first().removeClass('active-bg') ;
	     mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
	     mw.$(".pages_tree_item.item_"+active_item_is_parent).addClass('active-bg')
         mw.$(".pages_tree_item.item_"+active_item_is_parent).children().first().addClass('active')
     	 mw.$('#pages_edit_container').attr('data-parent-page-id',active_item_is_parent);
	 }
     else {
		mw.$('#pages_edit_container').removeAttr('data-parent-page-id');
	 }
     mw.$('#pages_edit_container').attr('data-page-id',$p_id);
     mw.$('#pages_edit_container').attr('data-type','content/edit_page');
     mw.$('#pages_edit_container').removeAttr('data-subtype');
     mw.$('#pages_edit_container').removeAttr('data-content-id');
	 mw.$('#pages_edit_container').removeAttr('content-id');
 
	 
	 
	 
	 
     mw.$(".mw_edit_page_right").css("overflow", "hidden");
     edit_load('content/edit_page');
}

mw.on.hashParam("parent-page", function(){
    var act = mw.url.windowHashParam("action");
    if(act == 'new:page'){
       mw.contentAction.create('page');
    }
});


mw.on.hashParam("action", function(){
  $(mwd.body).addClass("loading");
  window.scrollTo(0, 0);
  mw.$("#pages_edit_container").stop();
  mw.$('#pages_edit_container').removeAttr('mw_select_trash');
  mw.$(".mw_edit_page_right").css("overflow", "hidden");
  if(this==false) {
    mw.$(".mw_edit_page_right").css("overflow", "hidden");
    edit_load('content/manage');
    return false;
  }
  var arr = this.split(":");
  $(mwd.body).removeClass("action-Array");
  var cat_id = mw.url.windowHashParam("category_id");
  if(typeof cat_id != 'undefined'){
       mw.$('#pages_edit_container').attr('category_id',cat_id);
  }
  else{
      mw.$('#pages_edit_container').removeAttr('data-active-item');
  }
  if(arr[0]==='new'){
      mw.contentAction.create(arr[1]);
  }
  else{
      mw.$(".active-bg").removeClass('active-bg');
      mw.$(".mw_action_nav").removeClass("not-active");
      var active_item = mw.$(".item_"+arr[1]);
      if(arr[0] == 'showposts'){
            var active_item =  mw.$(".pages_tree_item.item_"+arr[1]);
      }
      else if(arr[0] == 'showpostscat'){
           var active_item =  mw.$(".category_element.item_"+arr[1]);
      }
      active_item.addClass('active-bg');
      active_item.parents("li").addClass('active');
      if(arr[0]==='editpage'){
        mw_select_page_for_editing(arr[1])
      }
      if(arr[0]==='trash'){
        mw_select_trash(arr[0])
      }
      else if(arr[0]==='showposts'){
        mw_set_edit_posts(arr[1])
      }
      else if(arr[0]==='showpostscat'){
        mw_set_edit_posts(arr[1], true)
      }
      else if(arr[0]==='editcategory'){
        mw_select_category_for_editing(arr[1])
      }
      else if(arr[0]==='editpost'){
          mw_select_post_for_editing(arr[1]);
      }
  }

});



edit_load = function(module){

  var n = mw.url.getHashParams(window.location.hash)['new_content'];

  if(n=='true'){
      var slide = false;
      mw.url.windowDeleteHashParam('new_content');
  }
  else{
      var slide = true;
  }
  mw.load_module(module,'#pages_edit_container');
}

function mw_select_category_for_editing($p_id){
      var  active_cat = $('#pages_tree_container_<?php print $my_tree_id; ?> li.category_element.active-bg').first();
      if(active_cat != undefined){
        var active_cat = active_cat.attr('data-category-id');
        mw.$('#pages_edit_container').attr('data-selected-category-id',active_cat);
      }
      else {
        mw.$('#pages_edit_container').removeAttr('data-selected-category-id');
      }
	  mw.$('#pages_edit_container').attr('data-category-id',$p_id);
      mw.$(".mw_edit_page_right").css("overflow", "hidden");
      edit_load('categories/edit_category');
}




function mw_set_edit_posts(in_page, is_cat, c){
      var is_cat = typeof is_cat === 'function' ? undefined : is_cat;
      var c =
      mw.$('#pages_edit_container').removeAttr('data-content-id');
      mw.$('#pages_edit_container').removeAttr('data-page-id');
      mw.$('#pages_edit_container').removeAttr('data-category-id');
      mw.$('#pages_edit_container').removeAttr('data-selected-category-id');
	    mw.$('#pages_edit_container').removeAttr('subtype');
	    mw.$('#pages_edit_container').removeAttr('data-subtype');
     mw.$('#pages_edit_container').removeAttr('data-content-id');
	 
	      mw.$('#pages_edit_container').removeAttr('is_shop');

	  	  mw.$('.mw-admin-go-live-now-btn').attr('content-id',in_page);
	  
	  
      if(in_page != undefined && is_cat == undefined){
         mw.$('#pages_edit_container').attr('data-page-id',in_page);
      }
      if(in_page != undefined && is_cat != undefined){
         mw.$('#pages_edit_container').attr('data-category-id',in_page);
         mw.$('#pages_edit_container').attr('data-selected-category-id',in_page);
      }
	  mw.load_module('content/manage','#pages_edit_container');
}


function mw_select_trash(c){
   mw.$('#pages_edit_container').removeAttr('data-content-id');
   mw.$('#pages_edit_container').removeAttr('data-page-id');
   mw.$('#pages_edit_container').removeAttr('data-category-id');
   mw.$('#pages_edit_container').removeAttr('data-selected-category-id');
   mw.$('#pages_edit_container').removeAttr('data-keyword');
   mw.load_module('content/trash','#pages_edit_container', function(){
        typeof c === 'function' ? c.call() : '';
   });
}

function mw_select_post_for_editing($p_id, $subtype){

	 var  active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
	 var active_item_is_page = active_item.attr('data-page-id');
        mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
        mw.$('#pages_edit_container').removeAttr('data-category-id');
        mw.$('#pages_edit_container').removeAttr('category_id');
		
		        mw.$('#pages_edit_container').removeAttr('page-id');

		
			  mw.$('.mw-admin-go-live-now-btn').attr('content-id',$p_id);

		
		
	  var active_item_is_category = active_item.attr('data-category-id');
	 if(active_item_is_category != undefined){
		 
			  mw.$('#pages_edit_container').attr('data-parent-category-id',active_item_is_category);
			  
			  var active_bg = mwd.querySelector('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg');
			 
			  var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'have_category');
			   
			   if(active_item_parent_page == false){
				 var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'is_page');
 
			   }
			   
			    if(active_item_parent_page == false){
				 var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'pages_tree_item');
  
			   }
			  
			   
			   if(active_item_parent_page != false){
					var active_item_is_page = active_item_parent_page.getAttribute('data-page-id');
					
			   }  

	 } else {
	    mw.$('#pages_edit_container').removeAttr('data-parent-category-id');

	 }
	  

	  if(active_item_is_page != undefined){
		 	 mw.$('#pages_edit_container').attr('data-parent-page-id',active_item_is_page);

	 } else {
		mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

	 }



	 mw.$('#pages_edit_container').removeAttr('data-subtype');
	 mw.$('#pages_edit_container').removeAttr('is_shop');
	 mw.$('#pages_edit_container').attr('data-content-id',$p_id);
	 if($subtype != undefined){
		 if($subtype == 'product'){
			  mw.$('#pages_edit_container').attr('is_shop', 'y');
		 }
	     mw.$('#pages_edit_container').attr('data-subtype', $subtype);
	 } else {
		mw.$('#pages_edit_container').attr('data-subtype', 'post');
	 }
    mw.$(".mw_edit_page_right").css("overflow", "hidden");
    edit_load('content/edit_post');
}

function mw_add_product(){
    mw_select_post_for_editing(0,   'product')
}

function mw_make_pages_tree_sortable(){
	$("#pages_tree_toolbar .pages_tree").sortable({
       axis:'y',
	   items: '>.pages_tree_item',
	   distance: 35,
	   containment: "parent",
       update:function(){
		    var obj = {ids:[]}
            $(this).find('.pages_tree_item').each(function(){
              var id = this.attributes['data-page-id'].nodeValue;
              obj.ids.push(id);
            });
		    $.post("<?php print api_link('content/reorder'); ?>", obj, function(){
			   mw.reload_module('#mw_page_layout_preview');
		    });
		 },
		 start:function(a,ui){

		},
         scroll:false
     });

      $("#pages_tree_toolbar .pages_tree .have_category").sortable({
          axis:'y',
          items: '.category_element',
          distance: 35,
          update:function(){
              var obj = {ids:[]}
              mw.$('.category_element', this).each(function(){
                  var id = this.attributes['data-category-id'].nodeValue;
                  obj.ids.push(id);
              });
              $.post("<?php print api_link('category/reorder'); ?>", obj, function(){
                mw.reload_module('#mw_page_layout_preview');
              });
          },
          start:function(a,ui){

          },
          scroll:false
      });
}




</script>

<div class="mw-ui-row" id="edit-content-row">
  <div class="mw-ui-col tree-column" <?php if($action=='posts'): ?>  <?php endif ?>>
    <div class="tree-column-holder">
      <div class="fixed-side-column scroll-height-exception-master">
        <div class="create-content scroll-height-exception"> <a href="javascript:;" class="mw-ui-btn create-content-btn" id="create-content-btn"><span class="mw-icon-plus"></span>Create New</a> </div>
        <div class="fixed-side-column-container mw-tree" id="pages_tree_container_<?php print $my_tree_id; ?>">
          <?php
		 $is_shop_str = " is_shop='n' ";
		 $is_shop_str = "   ";
	   if(isset($is_shop)){
		 $is_shop_str = " is_shop='{$is_shop}' ";
	   } elseif(isset($params['is_shop'])){
		 $is_shop_str = " is_shop='".$params['is_shop']."' ";
	   } elseif($action=='products'){
		 $is_shop_str = " is_shop='y' ";
	   }
	   if($action=='posts'){
		   $is_shop_str = " is_shop='n'  skip-static-pages='true' "; 
	   }
	   ?>
          <?php if($action=='pages'): ?>
          <module data-type="pages" template="admin" active_ids="<?php print $active_content_id; ?>" active_class="active-bg"   id="pages_tree_toolbar"  view="admin_tree" home_first="true"  />
          <?php elseif($action=='categories'): ?>
            
            <module skip-static-pages="true" data-type="pages" template="admin" active_ids="<?php print $active_content_id; ?>" active_class="active-bg"  include_categories="true" include_global_categories="true" id="pages_tree_toolbar" <?php print $is_shop_str ?>  view="admin_tree" home_first="true"  />
          
          <?php else: ?>
          <module data-type="pages" template="admin" active_ids="<?php print $active_content_id; ?>" active_class="active-bg"  include_categories="true" include_global_categories="true" id="pages_tree_toolbar" <?php print $is_shop_str ?>  view="admin_tree" home_first="true"  />
          <?php endif ?>
          <?php event_trigger('admin_content_after_website_tree',$params); ?>
        </div>
        <div class="tree-show-hide-nav scroll-height-exception"> <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.tree.openAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));">Open All</a> <a class="mw-ui-btn" href="javascript:;" onclick="mw.tools.tree.closeAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));">Close All</a> </div>
      </div>
    </div>
  </div>
  <div class="mw-ui-col main-content-column">
    <div class="mw-ui-col-container">
      <?php
        $ed_content = false;
        $content_id = '';

		if(isset($_GET['edit_content'])){
			 $content_id = ' data-content-id='.intval($_GET['edit_content']).' ';
			 $ed_content = true;
		} else {

				if(defined('CONTENT_ID')== true and CONTENT_ID != false and CONTENT_ID != 0){
					 $ed_content = true;
				 $content_id = ' data-content-id='.intval(CONTENT_ID).' ';

			  } else   if(defined('POST_ID')== true and POST_ID != false and POST_ID != 0){
				   $ed_content = true;
				 $content_id = ' data-content-id='.intval(POST_ID).' ';

			  } else if(defined('PAGE_ID') == true and PAGE_ID != false and PAGE_ID != 0 ){
				   $ed_content = true;
				  $content_id = ' data-content-id='.intval(PAGE_ID).' ';

			  }
		}

	   ?>
      <div id="pages_edit_container"  <?php print $is_shop_str ?>>
        <?php if( $action == false): ?>
			<?php if( $ed_content=== false): ?>
            	<module data-type="content/manage" page-id="global" id="edit_content_admin" <?php print  $content_id ?> <?php print $is_shop_str ?> />
            <?php else: ?>
            	<module data-type="content/manage" page-id="global" id="edit_content_admin" <?php print  $content_id ?>   />
            <?php endif; ?>
        <?php elseif( $action!= false and $action=='pages'): ?>
        	<module data-type="content/manage" page-id="global" id="edit_content_admin" content_type="page" <?php print  $content_id ?> <?php print $is_shop_str ?> />
        <?php elseif( $action!= false and $action=='posts'): ?>
        
        	<module data-type="content/manage" page-id="global" id="edit_content_admin" content_type="post" <?php print  $content_id ?>  subtype="post"  />
             <?php elseif( $action!= false and $action=='products'): ?>
        	<module data-type="content/manage" page-id="global" id="edit_content_admin" content_type="post" <?php print  $content_id ?> <?php print $is_shop_str ?> />
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $view = mw('url')->param('view'); ?>
<?php if( $view == 'content'){  ?>
<?php  show_help('content');  ?>
<?php } elseif($view == 'shop'){  ?>
<?php  show_help('shop');  ?>
<?php  } ?>
