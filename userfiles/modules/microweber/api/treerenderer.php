<?php if(!isset($my_tree_id)) { $my_tree_id = uniqid();} ?>

mw.treeRenderer = {
  edit_buttons:function(type, id, sort_button){
      if(type==='page'){
        var $str  = "\
        <span class='mw-icon-bin'  onclick='event.stopPropagation();mw.tools.tree.del("+id+");' title='<?php _e("Move to trash"); ?>'>\
          </span>\
        <span class='mw-icon-pen' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editpage:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
          </span>\
          ";

		  if(sort_button != undefined && sort_button == true){
			 var $str2  = "<span class='mw_sort_tree_handle ico iMove'  onclick='event.stopPropagation();return false;' onmousedown=\"mw.treeRenderer.makeSortable(this);\" title='<?php _e("Sort"); ?>'></span>";
		     $str = $str+$str2;
		  }

		  return  $str;
      }
      else if(type==='category'){
           var $str  = "\
            <span class='mw-icon-bin' onclick='event.stopPropagation();mw.tools.tree.del_category("+id+");' title='<?php _e("Move to trash"); ?>'>\
              </span>\
            <span class='mw-icon-pen' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editcategory:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
              </span>\
          ";


		   if(sort_button != undefined && sort_button == true){
			 var $str2  = "<span class='mw_sort_tree_handle ico iMove'  onclick='event.stopPropagation();return false;' onmousedown=\"mw.treeRenderer.makeSortable(this);\" title='<?php _e("Sort"); ?>'></span>";
		     $str = $str+$str2;
		   }
		  return  $str;

      }
  },

  makeSortable:function(handle){
    var  $is_sort = mw.$(handle).parents('[data-sortable]').first();
	if($is_sort != undefined){


		   var master = $is_sort;

    		  if(!master.hasClass("ui-sortable")){

         _mw_ui_will_init_tree = false;

               master.sortable({
                   items: 'li',

                   axis:'y',

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
            		     $.post("<?php print site_url('api/content/reorder'); ?>", obj, function(){
                    _mw_ui_will_init_tree = true;



                         /*


                         mw.reload_module("pages", function(){
                             mw.treeRenderer.appendUI('#pages_tree_toolbar');
                         });


                         */


            		     });
            		 }
            		 var cat_found = false;
            		  var obj = {ids:[]}
                     $(this).find('[data-category-id]').each(function(){
                         var id = this.attributes['data-category-id'].nodeValue;
                        obj.ids.push(id);
            			cat_found = true;
                     });

            		 if(cat_found == true){
            		    $.post("<?php print site_url('api/category/reorder'); ?>", obj, function(){


                _mw_ui_will_init_tree = true;


            		    });
            		 }




                  if(_mw_ui_will_init_tree != false){

                    _mw_ui_will_init_tree = false;



                      mw.reload_module("pages", function(){
                       if(!!mw.treeRenderer){
                                  mw.$(".mw_pages_posts_tree").removeClass("activated");

                                      var isel = $('#pages_tree_toolbar');

                                    if(isel.length > 0){
                                     mw.treeRenderer.appendUI('.mw_pages_posts_tree');

                                     mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
                                    }
                              }
                         });
                  }




                   },
                   start:function(a,ui){
                          //$(this).height($(this).outerHeight());
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
          var id = $(master).dataset("page-id");
          var subtype = $(master).dataset("subtype");
             var attr = master.attributes;




		  if(el != undefined){
		  var href = el.href;

           var is_content_in_admin = $(el).hasClass('mw-tree-renderer-admin-content-link-item');
          
         if(attr['data-page-id']!==undefined ){

           el.href = '<?php print admin_url() ?>view:content#action=showposts:'+id;
            if(subtype != 'dynamic'){
            el.href = '<?php print admin_url() ?>view:content#action=editpage:'+id;

            }



 // el.href = 'javascript:void(0);';

         } else {
         el.href = 'javascript:void(0);';
         }
          
          var html = el.innerHTML;
          var toggle = "";
          var show_posts = "";
         

          var toggle ='';


          var sub_page = '<span title="Add Sub-page" class="ico iplus mw_tree_sub_page" onclick="mw.url.windowHashParam(\'action\', \'new:page\');mw.url.windowHashParam(\'parent-page\', $(this.parentNode).dataset(\'page-id\'));mw.event.cancel(event);"></span>';
          var sub_page = '';

          // type: page or category
          if(attr['data-page-id']!==undefined){
              var pageid = attr['data-page-id'].nodeValue;
              if($(el.parentNode).children('ul').length>0){
                  var toggle = '<span onclick="mw.tools.tree.toggleit(this.parentNode,event,'+pageid+');mw.event.cancel(event);" class="mw_toggle_tree"></span>';
              }
              var show_posts = "<span class='mw_ed_tree_show_posts' title='<?php _e("Go Live edit."); ?>' onclick='event.stopPropagation();window.top.location.href=\""+href+"/editmode:y\"'></span>";
              var show_posts = "";

			 var  sort_content = false;

			if($is_sort != undefined){
		    var sort_content = true;
	  		}
			 el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw.treeRenderer.edit_buttons('page', pageid, sort_content) + toggle + show_posts + sub_page;
             el.setAttribute("onclick", "mw.tools.tree.openit(this,event,"+pageid+")");
             if( $(el).hasClass("content-unpublished") ) {
                //$(el).append("<span class='mw_ed_tree_publish' onclick='mw.post.set(" + id + ", \"publish\", event);' title='<?php _e("Publish"); ?>'></span>");
             }

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

              var show_posts = "<span class='mw_ed_tree_show_posts' title='<?php _e("Go Live edit."); ?>' onclick='event.stopPropagation();window.location.href=\""+href+"/editmode:y\"'></span>";
              var show_posts = "";
              el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw.treeRenderer.edit_buttons('category', pageid, sort_content) + toggle + show_posts;
              el.setAttribute("onclick", "mw.tools.tree.openit(this,event,"+pageid+");mw.event.cancel(event);");
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
          $(master).addClass(master.querySelector('li') === null ? '' : 'has-children')

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
      if(mwd.querySelector(holder)!==null && !$(mwd.querySelector(holder)).hasClass('activated') && mwd.querySelector(holder).querySelector(".mw_del_tree_content") === null ){
        $(mwd.querySelector(holder)).addClass('activated');
        var type = mw.tools.tree.detectType(mwd.querySelector(holder));




        if(type==='controller'){
           mw.treeRenderer.rendController(holder);
        }
        else if(type==='selector'){
           mw.treeRenderer.rendSelector(holder);
        }
/*
        var has_id = mwd.querySelector(holder).id;
        if(has_id != undefined){
         mw.on.moduleReload('#'+has_id, function(e){
         mw.log('appendUI '+has_id);
                mw.treeRenderer.appendUI('#'+has_id);

            });
        }

*/




        mw.tools.tree.recall(mwd.querySelector(holder));


    }
    else{

    }
  }
}