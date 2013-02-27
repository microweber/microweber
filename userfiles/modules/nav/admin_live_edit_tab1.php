<style type="text/css">
.mw_tabs_layout_simple .mw_simple_tabs_nav li {
	margin:0 11px;
}
.mw-ui-category-selector {
	width: auto;
}
body.module-settings-page .mw-ui-category-selector{
  width: 330px;
}

.mw-ui-category-selector .mw-ui-check:hover{
  background-color: white;
}

#custom_link_controller {
	width: auto;
	display: none;
	padding-bottom: 0;
}


body.module-settings-page #custom_link_controller{
  width: 330px;
}

#custom_link_controller input[type='text'] {
	width: 100%;
    max-width: 60%;
	max-width: -o-calc(100% - 130px);
    max-width: -webkit-calc(100% - 130px);
    max-width: calc(100% - 130px);
	margin-bottom: 20px;
}
.order-has-link .mw-modules-admin li > .module-nav-edit-item {
	box-shadow: 0 0 17px #555;
	background: white;
	padding: 0;
    z-index: 1;
}
.order-has-link .mw-modules-admin li > .module-nav-edit-item:hover {
	background: white;
}

.order-has-link .module_item{
  padding: 0;
}

.order-has-link .module_item .menu_element_link {
  display: block;
  padding: 12px 20px 12px 40px;
  top: 0;
}



.order-has-link .iMove{
  position: absolute;
  top: 9px;
  left: 7px;
  margin:0;
  z-index: 2;
}



#custom_link_inline_controller {
	background: white;
	border:none;
	padding-top: 0;
}

#custom_link_inline_controller .pages_tree{
  background: none;
}

#custom_link_inline_controller .pages_tree.depth-1 {
	padding-left: 0;
}
#custom_link_inline_controller .pages_tree li {
	background-color: transparent;
}
#custom_link_inline_controller input[type='text'] {
	float: left;
	width: 100%;
	margin-right:15px;
	max-width: 60%;
	max-width: -o-calc(100% - 110px);
    max-width: -webkit-calc(100% - 110px);
    max-width: calc(100% - 110px);
}
.menu_element_link {
	position: relative;
	top: 7px;
}
#custom_link_inline_controller .mw-ui-btn2 {
	width: 70px;
}
.pages_tree_link_text {
	max-width: none;
}
.custom_link_delete_header {
	padding-bottom: 12px;
	padding-right: 10px;
	text-align: right;
}
.custom_link_delete_header .mw-ui-delete {
	margin-right: 5px;
}
#add_new_menu_tab {
	display: none;
}
.mw_admin_modules_sortable_handle {
	visibility: hidden;
}
.module_item:hover .mw_admin_modules_sortable_handle {
	visibility: visible;
}
</style>
<script  type="text/javascript">
  mw.require('forms.js', true);
  mw.require('url.js', true);
</script>
<? if(!isset($rand )) {$rand  = uniqid();} ?>
<script type="text/javascript">



<?php include INCLUDES_DIR . 'api/treerenderer.php'; ?>


  mw.menu_save = function($selector){
      var obj = mw.form.serialize($selector);
      $.post("<? print site_url('api/add_new_menu') ?>",  obj, function(data){
	    window.location.href = window.location.href;
      });
 }




 requestLink = function(){

    mw.$("#menu-selector").toggle();
    mw.$("#custom_link_controller").hide();
 }

 requestCustomLink = function(){

    mw.$("#custom_link_controller").toggle();
    mw.$("#menu-selector").hide();

 }

 add_new_menu = function(){
   var add_new_menu_tab = mwd.querySelector('#add_new_menu_tab');

      mw.simpletab.set(add_new_menu_tab);


 }


  mw.menu_edit_items = function($menu_id, $selector){


  mw.$($selector).attr('menu-name',$menu_id);
   mw.load_module('nav/edit_items',$selector);


 }

 menuSelectorInit = function(selector){
     var selector = selector ||  "#menu-selector";
     mw.treeRenderer.appendUI(selector);
     mw.$(selector + ' input[type="radio"]').commuter(function(){

        var content_id =  mw.$(".module-nav-edit-item input[name='content_id']");
        var taxonomy_id =  mw.$(".module-nav-edit-item input[name='taxonomy_id']");

        var el = this;

        content_id.val('');
        taxonomy_id.val('');

        if(mw.tools.hasParentsWithClass(el, 'category_element')){
           taxonomy_id.val(el.value);
        }
        else if(mw.tools.hasParentsWithClass(el, 'pages_tree_item')){
            content_id.val(el.value);
        }
        mw.menu_save_new_item('.menu_item_edit');
        mw.$(selector).hide();
     });


 }

 view_all_subs = function(){
    var master = mwd.querySelector('.mw-modules-admin');
    $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function(){
      $(this).addClass('toggler-active');
      $(this.parentNode.parentNode.querySelector('ul')).addClass('toggle-active').show();
    });

    $(".view_all_subs").addClass('active');
    $(".hide_all_subs").removeClass('active');
 }

 hide_all_subs = function(){
    var master = mwd.querySelector('.mw-modules-admin');
    $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function(){
      $(this).removeClass('toggler-active');
      $(this.parentNode.parentNode.querySelector('ul')).removeClass('toggle-active').hide();
    });
    $(".view_all_subs").removeClass('active');
    $(".hide_all_subs").addClass('active');
 }

 cancel_editing_menu = function(id){
   $("#menu-item-"+id).removeClass('active');
   $("#edit-menu_item_edit_wrap-"+id).remove();
 }


$(document).ready(function(){

   menuSelectorInit();


});

 </script>
<? $menus = get_menu(); ?>
<?php


	if(isset( $params['menu_name'])){

		$menu_name =  $params['menu_name'];
	}elseif(isset( $params['name'])){

		$menu_name =  $params['name'];
	} else {
			$menu_name = get_option('menu_name', $params['id']);

	}
	$active_menu = false;
  $menu_id = false;
  if($menu_name != false){
  $menu_id = get_menu_id('title='.$menu_name);
  }

 ?>
<? if(isarr($menus) == true): ?>
<? if(isarr($menus )): ?>

<div class="control-group">
  <label class="mw-ui-label"><?php _e("Select the Menu you want to edit"); ?></label>
  <div class="mw-ui-select" style="width:100%">
    <select  name="menu_name" class="mw_option_field"   type="radio" data-refresh="nav" onchange="mw.menu_edit_items(this.value, '#items_list_<?  print $rand ?>');" >
      <? foreach($menus  as $item): ?>
      <option <?  if($menu_name == $item['title']): ?> <?  $active_menu = $item['title'] ?> selected="selected" <? endif; ?> value="<? print $item['title'] ?>"><? print $item['title'] ?></option>
      <? endforeach ; ?>
    </select>
  </div>
 <hr>
  <label class="mw-ui-label">Select from:</label>
  <a href="javascript:requestLink();" class="mw-ui-btn mw-ui-btn-medium"><span class="ico iplus"></span><span><?php _e("Add New Link"); ?></span></a> <a href="javascript:requestCustomLink();" class="mw-ui-btn mw-ui-btn-medium"><span class="ico iplus"></span><span><?php _e("Add Custom Link"); ?></span></a>
  <hr>
</div>
<? endif; ?>
<? else : ?>
You have no exising menus. Please create one.
<? endif; ?>
<div id="menu-selector" class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector">
  <microweber module="categories/selector"  for="content" to_table_id="<? print 0 ?>" input-type-categories="radio" input-name-categories="link_id" input-name="link_id"  />
</div>
<div id="custom_link_controller" class="mw-ui-gbox">
  <input type="text" class="mw-ui-field" placeholder="<?php _e("Title"); ?>" name="title" />
  <div class="mw_clear"></div>
  <input type="text" class="mw-ui-field" placeholder="<?php _e("URL"); ?>" name="url"  />
  <input type="hidden" name="parent_id" value="<?  print   $menu_id ?>" />
  <button class="mw-ui-btn2 mw-ui-btn-blue right" onclick="mw.menu_save_new_item('#custom_link_controller');">Add to menu</button>
</div>
<div class="vSpace"></div>
<?  //d( $active_menu); ?>
<? //.. d( $params); ?>
<div class="<? print $config['module_class']; ?> menu_items order-has-link"   id="items_list_<?  print $rand ?>">
  <? if($active_menu != false): ?>
    <h2><?php print $menu_name; ?> Links <label class="mw-ui-label"><small>Here you can edit your menu links. You can also drag and drop to reorder them.</small></label></h2>


  <span style="padding: 0;" class="posts-selector right"><span class="view_all_subs" onclick="view_all_subs();"><?php _e("View All"); ?></span>/<span class="hide_all_subs" onclick="hide_all_subs();"><?php _e("Hide All"); ?></span></span>

  <label class="mw-ui-label"><?php _e("Edit existing links/buttons"); ?></label>

  <div class="vSpace"></div>
  <module data-type="nav/edit_items"  menu-name="<?  print $active_menu ?>"  />
  <? endif; ?>
</div>
