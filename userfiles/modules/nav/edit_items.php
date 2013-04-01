<?
 if(is_admin() == false){
	 error('Must be admin');
 }
 $id = false;
 if(isset($params['menu-id'])){
 $id = intval($params['menu-id']);
 }


 if(isset($params['menu-name'])){
 //$id = trim($params['menu-name']);
 	$menu = get_menu('one=1&limit=1&title='.$params['menu-name']);
	if(isset($menu['id'])){
		$id = intval($menu['id']);

	}
 }

if( $id != 0){
	$menu_params = array();
	$menu_params['menu_id'] =  $id;
   //	$menu_params['link'] = '<div id="menu-item-{id}" class="module_item"><span onclick="mw.tools.toggle(this.parentNode.parentNode.querySelector(\'ul\'), this);" class="menu_nested_controll_arrow"></span><span class="ico iMove mw_admin_modules_sortable_handle"></span><a data-item-id="{id}" class="menu_element_link {active_class}" href="javascript:;" onclick="mw.menu_items_set_edit({id}, this);">{title}</a></div>';
	$menu_params['link'] = '<div id="menu-item-{id}" class="module_item"><span class="ico iMove mw_admin_modules_sortable_handle"></span><a data-item-id="{id}" class="menu_element_link {active_class}" href="javascript:;" onclick="mw.menu_items_set_edit({id}, this);">{title}</a></div>';

    $data = menu_tree( $menu_params);
}

?>
<?  $rand = uniqid(); ?>
<? if($data != false): ?>
<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">
  
  mw.require('<? print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js', true);
 </script>
<script  type="text/javascript">
  $(document).ready(function(){
    mw.$(".mw-modules-admin li").each(function(){
      if(!mw.tools.has(this, 'ul.menu')){
       //  this.querySelector('.menu_nested_controll_arrow').style.display = 'none';
      }
    });
  });
 </script>
<script  type="text/javascript">
    if(typeof mw.menu_save_new_item !== 'function'){
        mw.menu_save_new_item = function(selector){
        	mw.form.post(selector, '<? print api_url('edit_menu_item'); ?>', function(){
        		mw.reload_module('#<? print $params['id'] ?>');
        		if(window.parent != undefined && window.parent.mw != undefined){
        			window.parent.mw.reload_module('nav');
        		}
        	});
        }
    }
</script>
<script  type="text/javascript">

mw.menu_item_delete = function($item_id){
    mw.tools.confirm(mw.msg.del, function(){
    	 $.get("<?php print site_url('api/delete_menu_item'); ?>/"+$item_id, function(){
    		 	mw.$('#mw_admin_menu_items_sort_<? print $rand; ?>').find('li[data-item-id="'+$item_id+'"]').fadeOut();
                if(self !== parent && typeof parent.mw !== 'undefined'){
    		      window.parent.mw.reload_module('nav');
    	        }

    	  });
    });
}


mw.menu_items_set_edit = function($item_id, node){

if(typeof node === 'object'){
  var li = mw.tools.firstParentWithTag(node, 'li');
  var id = $(li).dataset('item-id');


  var master = mw.tools.firstParentWithClass(node, 'mw-modules-admin');

  mw.$('li .active', master).removeClass('active');

  $(node.parentNode).addClass('active');

  if(mw.$("#edit-menu_item_edit_wrap-"+id).length>0){
    return false;
  }
  else{

  }
}

var the_li = mw.$('#mw_admin_menu_items_sort_<? print $rand; ?>').find('li[data-item-id="'+$item_id+'"]');
    var edit_wrap = $('#menu_item_edit_wrap-'+$item_id);
    mw.$('.module-nav-edit-item').remove();
    the_li.find('.module_item').eq(0).after('<div id="edit-menu_item_edit_wrap-'+$item_id+'" item-id="'+$item_id+'"></div>');
       $('#edit-menu_item_edit_wrap-'+$item_id).attr('item-id',$item_id);
       $('#edit-menu_item_edit_wrap-'+$item_id).attr('menu-id','<? print $id?>');
       mw.load_module('nav/edit_item','#edit-menu_item_edit_wrap-'+$item_id, function(){
           mw.$('#custom_link_inline_controller').show();

           menuSelectorInit("#menu-selector-"+$item_id);
           mwd.querySelector('#custom_link_inline_controller input[type="text"]').focus();
       });
      $('#ed_menu_holder').hide();
}





mw.menu_items_sort_<? print $rand; ?> = function(){
  if(!mw.$("#mw_admin_menu_items_sort_<? print $rand; ?>").hasClass("ui-sortable")){
    $("#mw_admin_menu_items_sort_<? print $rand; ?> ul:first").nestedSortable({
       items: 'li',
	   listType: 'ul',
	   handle: '.iMove',
	   attribute : 'data-item-id',
       update:function(){
         var obj = {ids:[], ids_parents:{}}
         $(this).find('.menu_element').each(function(){
            var id = this.attributes['data-item-id'].nodeValue;
            obj.ids.push(id);
			var $has_p =  $(this).parents('.menu_element:first').attr('data-item-id');
			if($has_p != undefined){
			  obj.ids_parents[id] = $has_p;
			}
            else {
				var $has_p1 =  $('#ed_menu_holder').find('[name="parent_id"]').first().val();
    			if($has_p1 != undefined){
    			 	obj.ids_parents[id] =$has_p1;
    			}
			}
         });
         $.post("<?php print site_url('api/reorder_menu_items'); ?>", obj);
            if(self !== parent && typeof parent.mw !== 'undefined'){
			    parent.mw.reload_module('nav');
			 } else {
			    mw.reload_module('nav');
			 }
       },
       start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
       },
       placeholder: "custom-field-main-table-placeholder"
    });
  }
}

 $(document).ready(function(){
    mw.menu_items_sort_<? print $rand; ?>();
 });
 </script>

<div class="mw-modules-admin" id="mw_admin_menu_items_sort_<? print $rand; ?>"> <? print $data; ?> </div>
<? else: ?>
This menu is empty, please add items.
<? endif; ?>
<div>
  <module id="ed_menu_holder" data-type="nav/edit_item" item-id="0" menu-id="<? print $id ?>" />
</div>
