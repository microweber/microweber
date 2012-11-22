<?
 if(is_admin() == false){
	 error('Must be admin'); 
 }
 $id = false;
 if(isset($params['menu-id'])){
 $id = intval($params['menu-id']);
 }
 
if( $id != 0){
$data = menu_tree( $id);	
}
 
?>

<? if($data != false): ?>
<? $rand = uniqid(); ?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('<? print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js');
 </script>
 

<script  type="text/javascript">
 



mw.menu_items_sort_<? print $rand ?> = function(){
  if(!mw.$("#mw_admin_menu_items_sort_<? print $rand ?>").hasClass("ui-sortable")){
	  
	  
	  
	  
	  
  $("#mw_admin_menu_items_sort_<? print $rand ?> ul").nestedSortable({
       items: 'li',
	   listType: 'ul',
	   handle: 'a',
	   attribute : 'data-item-id',
	 //  toleranceElement: '> li',
       axis:'y',
     //  handle:'.mw_admin_posts_sortable_handle',
       update:function(){
         var obj = {ids:[], ids_parents:{}}
         $(this).find('.menu_element').each(function(){
            var id = this.attributes['data-item-id'].nodeValue;
            obj.ids.push(id);
			
			$has_p =  $(this).parents('.menu_element:first').attr('data-item-id');
			if($has_p != undefined){
			 
			
				 obj.ids_parents[id] = $has_p;
			}
			
			
         });
		 
		// obj = $("#mw_admin_menu_items_sort_<? print $rand ?> ul").nestedSortable('serialize');

         $.post("<?php print site_url('api/reorder_menu_items'); ?>", obj, function(){
			  mw.reload_module('nav');
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


mw.menu_items_sort_<? print $rand ?>()
 </script>
























<div id="mw_admin_menu_items_sort_<? print $rand ?>">
<? print $data; ?>
</div>



<? else: ?>
This menu is empty, please add items.
<? endif; ?>

<div>
<module data-type="nav/edit_item" item-id="0" />



</div>
