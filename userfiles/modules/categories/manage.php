<div class="mw-module-category-manager">
  <label class="mw-ui-label">
    <?php _e("Select category to edit"); ?>
  </label>
  <div class="mw-ui-category-selector mw-ui-manage-list" id="mw-ui-category-selector-manage" style="visibility: visible;display: block">
    <?php
$field_name="categories";
$selected = 0;
$tree = array();
$tree['ul_class'] = 'pages_tree cat_tree_live_edit';
$tree['li_class'] = 'sub-nav';
$tree['rel'] = 'content';
$tree['link'] = "<a href='javascript:mw.quick_cat_edit({id})'><span class='ico icategory'></span>{title}</a>";
 category_tree($tree);
?>
  </div>
  <script type="text/javascript">


    mw.live_edit_load_cats_list = function () {
       
        mw.load_module('categories/manage', '#mw_add_cat_live_edit', function () {

        });
    }
    mw.quick_cat_edit = function ($id) {
        
        if ($id == undefined) {
            mw.$("#mw_select_cat_to_edit_dd").val();
        }
        mw.$("#mw_edit_category_admin_holder").attr("data-category-id", $id);
        mw.load_module('categories/edit_category', '#mw_edit_category_admin_holder', function () {
            $(mwd.body).removeClass("loading");
        });
    }

    
</script>
  <script type="text/javascript">



	mw.on.moduleReload("<?php print $params['id'] ?>", function(){
		mw.manage_cat_sort();

		
	 });





mw.manage_cat_sort = function(){


mw.$("#<?php print $params['id'] ?>").sortable({
     items: '.category_element',
     axis:'y',
     handle:'a',
     update:function(){
       var obj = {ids:[]}
       $(this).find('.category_element').each(function(){
          var id = this.attributes['value'].nodeValue;
          obj.ids.push(id);
       });
       $.post("<?php print api_link('category/reorder'); ?>", obj, function(){
          if(self !== parent && !!parent.mw){
            parent.mw.reload_module('categories');
          }
       });
     },
     start:function(a,ui){
      $(this).height($(this).outerHeight());
      $(ui.placeholder).height($(ui.item).outerHeight())
      $(ui.placeholder).width($(ui.item).outerWidth())
    },
    scroll:false
});

 
}
//mw.manage_cat_sort();


</script>
  <hr>
  <a href='javascript:mw.quick_cat_edit(0)' class="mw-ui-btn"><span class="ico iplus"></span>Add new category</a> </div>
<div id="mw_edit_category_admin_holder"></div>