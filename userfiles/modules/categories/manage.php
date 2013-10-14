<?php 


 

$field_name="categories";
$selected = 0;
$tree = array();
$tree['rel'] = 'content';
$tree['link'] = "<a   href='javascript:mw.load_quick_cat_edit({id})'>{title}</a>";

mw('category')->tree($tree);
?>
<script type="text/javascript">
 mw.load_quick_cat_edit = function($id){
	     mw.$("#mw_quick_edit_category").attr("data-category-id",$id);
	     mw.load_module('categories/edit_category', '#mw_quick_edit_category', function(){
       })
 	}
	
	
mw.manage_cat_sort = function(){
   
	  
   mw.$("#<?php print $params['id'] ?>").sortable({
     items: '.category_element',
     axis:'y',
     handle:'a',
	 
	 
	 
	 
	 
	 
	 
	 
	//  connectWith: "#<?php print $params['id'] ?> .category_tree ul",
     update:function(){
       var obj = {ids:[]}
       $(this).find('.category_element').each(function(){
        var id = this.attributes['value'].nodeValue;
        obj.ids.push(id);
      });

       $.post("<?php print site_url('api/category/reorder'); ?>", obj, function(){});
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
mw.manage_cat_sort();	
</script>

<a href='javascript:mw.load_quick_cat_edit(0)'>+Add new category</a>
<div id="mw_quick_edit_category"></div>
