<?php //$rand = uniqid(); ?>
<?php $my_tree_id = crc32(url_string()); ?>
<? $active_content_id = 0;
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




mw.treeRenderer = {
  edit_buttons:function(type, id, sort_button){
      if(type==='page'){
        var $str  = "\
        <span class='mw_del_tree_content' onclick='event.stopPropagation();mw.tools.tree.del("+id+");' title='<?php _e("Delete"); ?>'>\
              <?php _e("Delete"); ?>\
          </span>\
        <span class='mw_ed_tree_content' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editpage:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
              <?php _e("Edit"); ?>\
          </span>\
          ";
		  
		  if(sort_button != undefined && sort_button == true){
			 var $str2  = "<span class='mw_sort_tree_handle ico iMove'  onclick='event.stopPropagation();return false;' onmousedown=\"mw.treeRenderer.makeSortable(this);\" title='<?php _e("Soft"); ?>'><?php _e("Soft"); ?></span>";
		     $str = $str+$str2;
		  }
		  
		  return  $str;
      }
      else if(type==='category'){
           var $str  = "\
            <span class='mw_del_tree_content' onclick='event.stopPropagation();mw.tools.tree.del_category("+id+");' title='<?php _e("Delete"); ?>'>\
                  <?php _e("Delete"); ?>\
              </span>\
            <span class='mw_ed_tree_content' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editcategory:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
                  <?php _e("Edit"); ?>\
              </span>\
          ";
		  

		   if(sort_button != undefined && sort_button == true){
			 var $str2  = "<span class='mw_sort_tree_handle ico iMove'  onclick='event.stopPropagation();return false;' onmousedown=\"mw.treeRenderer.makeSortable(this);\" title='<?php _e("Soft"); ?>'><?php _e("Soft"); ?></span>";
		  $str = $str+$str2;
		  }
		  return  $str;

      }
  },

  makeSortable:function(handle){
    var  $is_sort = mw.$(handle).parents('[data-sortable]').first();
	if($is_sort != undefined){


// mw.$($is_sort).children('.category_tree').each(function(){
        //  var master = $(this);

		   var master = $is_sort;
		  
    		  if(!master.hasClass("ui-sortable")){



               master.sortable({
                   items: 'li',
				     
                   axis:'y',
				   containment: master,
				 // containment: '.category_tree',
            	   //containment: $is_sort,
                   handle:'.mw_sort_tree_handle',
                   update:function(){
                     var obj = {ids:[]}
            		 var cont_found = false;
                     $(this).find('[data-page-id]').each(function(){
                         var id = this.attributes['data-page-id'].nodeValue;
                        obj.ids.push(id);
            			cont_found = true;
                     });
            		 if(cont_found == true){
            		     $.post("<?php print site_url('api/reorder_content'); ?>", obj, function(){});
            		 }
            		 var cat_found = false;
            		  var obj = {ids:[]}
                     $(this).find('[data-category-id]').each(function(){
                         var id = this.attributes['data-category-id'].nodeValue;
                        obj.ids.push(id);
            			cat_found = true;
                     });

            		 if(cat_found == true){
            		    $.post("<?php print site_url('api/reorder_categories'); ?>", obj, function(){});
            		 }
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


//});



	}
 
  },

  rendController:function(holder){
	 var  $is_sort = mw.$(holder).attr('data-sortable');

	  
      mw.$(holder+' li').each(function(){
          var master = this;
          var el = master.querySelector('a');
          
		  
		  if(el != undefined){
		  var href = el.href;
          el.href = 'javascript:void(0);';
          var html = el.innerHTML;
          var toggle = "";
          var show_posts = "";
          var attr = master.attributes;

          var toggle ='';

          // type: page or category
          if(attr['data-page-id']!==undefined){
              var pageid = attr['data-page-id'].nodeValue;
              if($(el.parentNode).children('ul').length>0){
                  var toggle = '<span onclick="mw.tools.tree.toggleit(this.parentNode,event,'+pageid+')" class="mw_toggle_tree"></span>';
              }
              var show_posts = "<span class='mw_ed_tree_show_posts' title='<?php _e("Go Live edit"); ?>' onclick='event.stopPropagation();window.top.location.href=\""+href+"/editmode:y\"'></span>";
             
			 var  sort_content = false;
			  
			if($is_sort != undefined){
		    var sort_content = true;
	  		}
			 el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw.treeRenderer.edit_buttons('page', pageid, sort_content) + toggle + show_posts;
              el.setAttribute("onclick", "mw.tools.tree.openit(this,event,"+pageid+")");
			  
			  
          }
          else if(attr['data-category-id']!==undefined){
              var pageid = attr['data-category-id'].nodeValue;
              if($(el.parentNode).children('ul').length>0){
                  var toggle = '<span onclick="mw.tools.tree.toggleit(this.parentNode,event,'+pageid+')" class="mw_toggle_tree"></span>';
              }
			  	 var  sort_content = false;
			  
			if($is_sort != undefined){
		    var sort_content = true;
	  		}
			
			
              var show_posts = "<span class='mw_ed_tree_show_posts' title='<?php _e("Go Live edit"); ?>' onclick='event.stopPropagation();window.location.href=\""+href+"/editmode:y\"'></span>";
              el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw.treeRenderer.edit_buttons('category', pageid, sort_content) + toggle + show_posts;
              el.setAttribute("onclick", "mw.tools.tree.openit(this,event,"+pageid+");");
          }
		  
		  
		  }
		  
      });
	  
	  
	  
  },
  rendSelector:function(holder){
       mw.$(holder+' li').each(function(){

          var master = this;
          var el = master.querySelector('label');
          el.setAttribute("onclick", "mw.tools.tree.checker(this);");
          var html = el.innerHTML;
          var toggle = "";

          var attr = master.attributes;

          // type: page or category
          if(attr['data-page-id']!==undefined){
              var pageid = attr['data-page-id'].nodeValue;
              if($(el.parentNode).children('ul').length>0){
                  var toggle = '<span onclick="mw.tools.tree.toggle(this.parentNode,event,'+pageid+')" class="mw_toggle_tree"></span>';
              }
              el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>'  + toggle;
          }
          else if(attr['data-category-id']!==undefined){
              var pageid = attr['data-category-id'].nodeValue;
              if($(el.parentNode).children('ul').length>0){
                  var toggle = '<span onclick="mw.tools.tree.toggle(this.parentNode,event,'+pageid+')" class="mw_toggle_tree"></span>';
              }

              el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + toggle;
          }
       });
  },
  appendUI:function(tree){
      var holder = tree || "#pages_tree_container_<?php print $my_tree_id; ?>";
      if(mwd.querySelector(holder)!==null){
        var type = mw.tools.tree.detectType(mwd.querySelector(holder));

        if(type==='controller'){
           mw.treeRenderer.rendController(holder);
        }
        else if(type==='selector'){
           mw.treeRenderer.rendSelector(holder);
        }
        mw.tools.tree.recall(mwd.querySelector(holder));
       // mw.log(mwd.querySelector(holder).id);
      //  mw.log(mw.cookie.ui("tree_"+mwd.querySelector(holder).id));
    }
  }
}



$(document).ready(function(){


 



    mw.treeRenderer.appendUI();

 mw.treeRenderer.appendUI('.page_posts_list_tree');
    mw.on.hashParam("page-posts", function(){
        mw_set_edit_posts(this);
    });
	
	
	if(mw.url.windowHashParam ("action") === undefined){
		}




    mw.on.moduleReload("pages_tree_toolbar", function(){
        mw.treeRenderer.appendUI();

    });

    mw.on.moduleReload("pages_edit_container", function(){
        mw.treeRenderer.appendUI("#pages_edit_container .page_posts_list_tree");

    });




   $(mwd.body).ajaxStop(function(){
      $(this).removeClass("loading");
  });




});







function mw_delete_content($p_id){
	 mw.$('#pages_edit_container').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#pages_edit_container');
}





function mw_select_page_for_editing($p_id){

 
 
 	 var  active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
	 var active_item_is_page = active_item.attr('data-page-id');
	
	 
	 
	  var active_item_is_category = active_item.attr('data-category-id');
	 if(active_item_is_category != undefined){
			  mw.$('#pages_edit_container').attr('data-parent-category-id',active_item_is_category);
			  var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.have_category').first();
			   if(active_item_parent_page != undefined){
					var active_item_is_page = active_item_parent_page.attr('data-page-id');
				   
			   } else {
				  var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.is_page').first();
				   if(active_item_parent_page != undefined){
						var active_item_is_page = active_item_parent_page.attr('data-page-id');
					   
				   }   
				   
			   }
  
  
	 } else {
	    mw.$('#pages_edit_container').removeAttr('data-parent-category-id');

	 }
	 
	  if(active_item_is_page != undefined){
		 	 mw.$('#pages_edit_container').attr('data-parent-page-id',active_item_is_page);

	 } else {
		mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

	 }
	
	
	
    mw.$('#pages_edit_container').attr('data-page-id',$p_id);
    mw.$('#pages_edit_container').attr('data-type','content/edit_page');
    mw.$('#pages_edit_container').removeAttr('data-subtype');
    mw.$('#pages_edit_container').removeAttr('data-content-id');
    mw.load_module('content/edit_page','#pages_edit_container', function(){
     //    mw.templatePreview.view(0);
    });
}




mw.on.hashParam("action", function(){

  if(this==false) {
    mw.load_module('content/manage','#pages_edit_container');
    return false;
  }

  var arr = this.split(":");

  $(mwd.body).removeClass("action-Array");





   // mw.$('#pages_edit_container').removeAttr('data-page-number');
	//mw.$('#pages_edit_container').removeAttr('data-paging-param');

   // mw.$('#pages_edit_container').attr('data-active-item',active_item);



  if(arr[0]==='new'){

      if(arr[1]==='page'){
        mw_select_page_for_editing(0);
      }
      else if(arr[1]==='post'){
        mw_select_post_for_editing(0);
      }
      else if(arr[1]==='category'){
        mw_select_category_for_editing(0);
      }
      else if(arr[1]==='product'){
        mw_add_product(0);
      }

     mw.$(".mw_action_nav").addClass("not-active");
     mw.$(".mw_action_"+arr[1]).removeClass("not-active");
  }
  else{
        //mw.url.windowHashParam("pg", 1);
      mw.$(".active-bg").removeClass('active-bg');
      mw.$(".mw_action_nav").removeClass("not-active");
      var active_item = mw.$(".item_"+arr[1]);

      active_item.addClass('active-bg');
      active_item.parents("li").addClass('active');
      if(arr[0]==='editpage'){
        mw_select_page_for_editing(arr[1])
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






















function mw_select_category_for_editing($p_id){
	
	
					  var  active_cat = $('#pages_tree_container_<?php print $my_tree_id; ?> li.category_element.active-bg').first();
				   if(active_cat != undefined){
						var active_cat = active_cat.attr('data-category-id');
					   	 mw.$('#pages_edit_container').attr('data-selected-category-id',active_cat);
				   } else {
					   	   mw.$('#pages_edit_container').removeAttr('data-selected-category-id');

				   }
	
 
	
	
	 mw.$('#pages_edit_container').attr('data-category-id',$p_id);
  	 mw.load_module('categories/edit_category','#pages_edit_container');
}




function mw_set_edit_posts($in_page, $is_cat){
       mw.$('#pages_edit_container').removeAttr('data-content-id');
	 mw.$('#pages_edit_container').removeAttr('data-page-id');
      mw.$('#pages_edit_container').removeAttr('data-category-id');
	   mw.$('#pages_edit_container').removeAttr('data-selected-category-id');

if($in_page != undefined && $is_cat == undefined){
 mw.$('#pages_edit_container').attr('data-page-id',$in_page);
}

if($in_page != undefined && $is_cat != undefined){
 mw.$('#pages_edit_container').attr('data-category-id',$in_page);
 mw.$('#pages_edit_container').attr('data-selected-category-id',$in_page);
}

	 mw.load_module('content/manage','#pages_edit_container', function(){




	 });






	 





}




function mw_select_post_for_editing($p_id, $subtype){
	
	 var  active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
	 var active_item_is_page = active_item.attr('data-page-id');
	
	 
	 
	  var active_item_is_category = active_item.attr('data-category-id');
	 if(active_item_is_category != undefined){
			  mw.$('#pages_edit_container').attr('data-parent-category-id',active_item_is_category);
			  var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.have_category').first();
			   if(active_item_parent_page != undefined){
					var active_item_is_page = active_item_parent_page.attr('data-page-id');
				   
			   } else {
				  var  active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.is_page').first();
				   if(active_item_parent_page != undefined){
						var active_item_is_page = active_item_parent_page.attr('data-page-id');
					   
				   }   
				   
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
  	 mw.load_module('content/edit_post','#pages_edit_container');
}

function mw_add_product(){
	
	
	 mw_select_post_for_editing(0,   'product')
 
	
 
}








</script>

<div id="mw_edit_pages">
  <div id="mw_edit_pages_content">
    <div class="mw_edit_page_left" id="mw_edit_page_left">
      <div class="mw_edit_pages_nav">
        <?php
            $view = url_param('view');
            if($view=='shop'){
        ?>
        <a href="<?php print admin_url(); ?>view:shop" class="mw_tree_title mw_tree_title_shop">
        <?php _e("My Online Shop"); ?>
        </a>
        <?php } else { ?>
        <a href="<?php print admin_url(); ?>view:content" class="mw_tree_title">
        <?php _e("Website  Navigation"); ?>
        </a>
        <?php } ?>
        <a href="#action=new:page" class="mw_action_nav mw_action_page" onclick="mw.url.windowHashParam('action','new:page');return false;">
        <label>Page</label>
        <button></button>
        </a> <a href="#action=new:post" class="mw_action_nav mw_action_post" onclick="mw.url.windowHashParam('action','new:post');return false;">
        <label>Post</label>
        <button>&nbsp;</button>
        </a> <a href="#action=new:category" class="mw_action_nav mw_action_category" onclick="mw.url.windowHashParam('action','new:category');return false;">
        <label>Category</label>
        <button>&nbsp;</button>
        </a> <a href="#action=new:product" class="mw_action_nav mw_action_product" onclick="mw.url.windowHashParam('action','new:product');">
        <label>Product</label>
        <button>&nbsp;</button>
        </a>
        <?php /*  <button onclick="mw_set_edit_categories()">mw_set_edit_categories</button>
        <button onclick="mw_set_edit_posts()">mw_set_edit_posts</button>
 */ ?>
      </div>
      <div class="mw_pages_posts_tree mw-tree"  id="pages_tree_container_<?php print $my_tree_id; ?>">
        <?
	  $is_shop_str = '';
	   if(isset($is_shop)){
		 $is_shop_str = " is_shop='{$is_shop}' "   ;
	   }
	   ?>
        <module data-type="pages_menu" active_ids="<? print $active_content_id; ?>" active_class="active-bg"  include_categories="true" include_global_categories="true" id="pages_tree_toolbar" <? print $is_shop_str ?>    />
        <div class="mw-clear"></div>
      </div>
      <div class="tree-show-hide-nav"> <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.tree.openAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));">Open All</a> <a class="mw-ui-btn" href="javascript:;" onclick="mw.tools.tree.closeAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));">Close All</a> </div>
    </div>
    <div class="mw_edit_page_right"> 
      <script>

  /*  $(document).ready(function(){

        var def = '<?php _e("Search for posts"); ?>';
        var field = mw.$("#mw-search-field");
        field.bind('keyup focus blur', function(event){
           mw.form.dstatic(event, def);
           if(event.type=='keyup'){
              mw.on.stopWriting(this, function(){
                 this.value !== def ? mw.url.windowHashParam('search',this.value) : '';
              });
           }
        });



    });
*/



    </script>
      <div class="" style="padding-left: 0;">
        <div class="top_label"><a href="#">See the tutorials here</a>.</div>
        <div class="vSpace"></div>
      </div>
      <?
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
      <div id="pages_edit_container"  <? print $is_shop_str ?>>
        <? if( $ed_content=== false): ?>
        <module data-type="content/manage" page-id="global" id="edit_content_admin" <? print  $content_id ?> <? print $is_shop_str ?> />
        <? else: ?>
        <div id="edit_content_admin" <? print  $content_id ?>></div>
        <? endif; ?>
      </div>
    </div>
  </div>
</div>
