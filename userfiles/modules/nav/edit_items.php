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
	$menu_params['link'] = '<div class="module_item"><span class="ico iMove mw_admin_modules_sortable_handle"></span><a data-item-id="{id}" class="menu_element_link {active_class}" href="javascript:mw.menu_items_set_edit({id});">{title}</a> <span class="mw-ui-close" onclick="mw.menu_item_delete({id});">[x]</span></div>';

$data = menu_tree( $menu_params);
}

?>
<?  $rand = uniqid(); ?>
<? if($data != false): ?>

<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('<? print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js');
 </script>


<script  type="text/javascript">

mw.menu_item_delete = function($item_id){


	 $.get("<?php print site_url('api/delete_menu_item'); ?>/"+$item_id, function(){
		 	mw.$('#mw_admin_menu_items_sort_<? print $rand; ?>').find('li[data-item-id="'+$item_id+'"]').fadeOut();
 if(window.parent != undefined && window.parent.mw != undefined){

		      window.parent.mw.reload_module('nav');

	 }


	  });


}


mw.menu_items_set_edit = function($item_id){

var the_li = mw.$('#mw_admin_menu_items_sort_<? print $rand; ?>').find('li[data-item-id="'+$item_id+'"]');
var edit_wrap = $('#menu_item_edit_wrap-'+$item_id);

 if(edit_wrap.length ==0){
	// the_li.append('<div id="menu_item_edit_wrap-'+$item_id+'" item-id="'+$item_id+'"></div>');
 }


 $('#ed_menu_holder').attr('item-id',$item_id);
 mw.reload_module('#ed_menu_holder');



}





mw.menu_items_sort_<? print $rand; ?> = function(){
  if(!mw.$("#mw_admin_menu_items_sort_<? print $rand; ?>").hasClass("ui-sortable")){





  $("#mw_admin_menu_items_sort_<? print $rand; ?> ul").nestedSortable({
       items: 'li',
	   listType: 'ul',
	   handle: '.iMove',
	   attribute : 'data-item-id',
	 //  toleranceElement: '> li',

     //  handle:'.mw_admin_posts_sortable_handle',
       update:function(){
         var obj = {ids:[], ids_parents:{}}
         $(this).find('.menu_element').each(function(){
            var id = this.attributes['data-item-id'].nodeValue;
            obj.ids.push(id);

			$has_p =  $(this).parents('.menu_element:first').attr('data-item-id');
			if($has_p != undefined){


				 obj.ids_parents[id] = $has_p;
			} else {
//obj.ids_parents[0] = id;

			}


         });

		// obj = $("#mw_admin_menu_items_sort_<? print $rand; ?> ul").nestedSortable('serialize');

         $.post("<?php print site_url('api/reorder_menu_items'); ?>", obj, function(resp){
			 if(window.parent != undefined && window.parent.mw != undefined){
				   window.parent.mw.reload_module('nav');
				   d(resp);
			 } else {
				  mw.reload_module('nav');
			 }

			 });
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

 $(mwd).ready(function(){

mw.menu_items_sort_<? print $rand; ?>()
 });
 </script>
























<div class="mw-modules-admin" id="mw_admin_menu_items_sort_<? print $rand; ?>">
    <? print $data; ?>
</div>



<? else: ?>
This menu is empty, please add items.
<? endif; ?>

<div>
<module id="ed_menu_holder" data-type="nav/edit_item" item-id="0" menu-id="<? print $id ?>" />



</div>
