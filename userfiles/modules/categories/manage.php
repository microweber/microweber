<div class="mw-module-category-manager">
   <a href='javascript:mw.quick_cat_edit_create(0)' class="mw-ui-btn pull-right mw-ui-btn-invert"><span class="mw-icon-plus"></span><span class="mw-icon-category"></span><?php _e("New category"); ?></a>
  <h2 style="margin-top: 0">
    <span class="mw-icon-category"></span>
    <?php _e("Select category to edit"); ?>
  </h2>
  <div class="mw-ui-category-selector mw-ui-manage-list" id="mw-ui-category-selector-manage" style="visibility: visible;display: block">
    <?php
$field_name="categories";
$selected = 0;
$tree = array();
$tree['ul_class'] = 'pages_tree cat_tree_live_edit';
$tree['li_class'] = 'sub-nav';
$tree['rel_type'] = 'content';

if(isset($params['page-id']) and $params['page-id'] != false){
	$tree['rel_id'] = intval($params['page-id']);
}



$tree['link'] = "<a href='javascript:mw.quick_cat_edit({id})'><span class='mw-icon-category'></span>&nbsp;{title}</a>";
 category_tree($tree);
?>
  </div>
  <script type="text/javascript">


    mw.live_edit_load_cats_list = function () {
        mw.load_module('categories/manage', '#mw_add_cat_live_edit', function () {

        });
    }
    mw.quick_cat_edit = function (id) {
        mw.tools.loading(mwd.body)
        mw.$("#mw_edit_category_admin_holder").attr("data-category-id", id);
        mw.$(".mw-module-category-manager").hide();
        mw.$("#mw-live-edit-cats-tab").removeClass('active');
        mw.load_module('categories/edit_category', '#mw_edit_category_admin_holder', function () {
            mw.tools.loading(mwd.body, false);
        });
    }

     mw.quick_cat_edit_create = function (id) {
       mw.tools.loading(mwd.body)
        mw.$("#mw_edit_category_admin_holder").attr("category-id", id);
		<?php if(isset($params['page-id']) and $params['page-id'] != false): ?>
        mw.$("#mw_edit_category_admin_holder").attr("page-id", '<?php print $params['page-id'] ?>');

		<?php endif; ?>
        mw.$(".mw-module-category-manager").hide();
        mw.$("#mw-live-edit-cats-tab").removeClass('active');
        mw.load_module('categories/edit_category', '#mw_edit_category_admin_holder', function () {
            mw.tools.loading(mwd.body, false)
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

   </div>
<div id="mw_edit_category_admin_holder"></div>