<?php 


 

$field_name="categories";
$selected = 0;
$tree = array();
$tree['rel'] = 'content';
$tree['link'] = "<a href='javascript:mw.load_quick_cat_edit({id})'>{title}</a>";

mw('category')->tree($tree);
?>
<script type="text/javascript">
 mw.load_quick_cat_edit = function($id){
	     mw.$("#mw_quick_edit_category").attr("data-category-id",$id);
	     mw.load_module('categories/edit_category', '#mw_quick_edit_category', function(){
       })
 	}
</script>

<a href='javascript:mw.load_quick_cat_edit(0)'>+Add new category</a>
<div id="mw_quick_edit_category"></div>
