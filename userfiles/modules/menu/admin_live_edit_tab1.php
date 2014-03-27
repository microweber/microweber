<style type="text/css">
.mw_tabs_layout_simple .mw_simple_tabs_nav li {
	margin:0 11px;
}
.mw-ui-category-selector {
	width: auto;
}
body.module-settings-page .mw-ui-category-selector {
	width: 330px;
}
.mw-ui-category-selector .mw-ui-check:hover {
	background-color: white;
}
#custom_link_controller {
	width: auto;
	display: none;
	padding-bottom: 0;
}
body.module-settings-page #custom_link_controller {
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
.order-has-link .mw-modules-admin li > .module-menu-edit-item {
	box-shadow: 0 0 17px #555;
	background: white;
	padding: 0;
	z-index: 1;
}
.order-has-link .mw-modules-admin li > .module-menu-edit-item:hover {
	background: white;
}
.order-has-link .module_item {
	padding: 0;
}
.order-has-link .module_item .menu_element_link {
	display: block;
	padding: 12px 20px 12px 40px;
	top: 0;
}
.order-has-link .module_item .menu_element_link:hover {
	text-decoration: underline
}
.order-has-link .iMove {
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
#custom_link_inline_controller .pages_tree {
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
<?php if(!isset($rand )) {$rand  = uniqid();} ?>
<script type="text/javascript">



<?php include MW_INCLUDES_DIR . 'api/treerenderer.php'; ?>


  mw.menu_save = function($selector){
      var obj = mw.form.serialize($selector);
      $.post("<?php print api_link('content/menu_create') ?>",  obj, function(data){
	    window.location.href = window.location.href;
		
		 menuSelectorInit();
		
 	   /* mw.$('#<?php print $params['id'] ?>').attr('new-menu-id',data);
		mw.reload_module('#<?php print $params['id'] ?>');
		menuSelectorInit();*/
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

 mw.menu_delete = function($id){
var data = {}
data.id = $id

var r=confirm("Are you sure you want to delete this menu?")
if (r==true)
  {
  
  
   $.post("<?php print api_link('content/menu_delete') ?>",  data, function(resp){
	   		  mw.reload_module('#<?php print $params['id'] ?>');
			   menuSelectorInit();
      });
  
  }

     

 }



mw.menu_edit_items = function($menu_id, $selector){

   mw.$($selector).attr('menu-name',$menu_id);
   
   
   mw.load_module('menu/edit_items',$selector);
    menuSelectorInit();


 }

 menuSelectorInit = function(selector){



     var selector = selector ||  "#menu-selector";
     mw.treeRenderer.appendUI(selector);
	 
	 var items =  mw.$(selector + ' input[type="radio"]');
	 
	 if(items == null){
		return; 
	 }
	  if(items.commuter == undefined){
		  return; 
		  
	  }
    items.commuter(function(){

        var content_id =  mw.$(".module-menu-edit-item input[name='content_id']");
        var categories_id =  mw.$(".module-menu-edit-item input[name='categories_id']");

        var el = this;

        content_id.val('');
        categories_id.val('');

        if(mw.tools.hasParentsWithClass(el, 'category_element')){
           categories_id.val(el.value);
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
<script  type="text/javascript">
    if(typeof mw.menu_save_new_item !== 'function'){
        mw.menu_save_new_item = function(selector,no_reload){
        	mw.form.post(selector, '<?php print api_link('content/menu_item_save'); ?>', function(){
				
				mw.$('#<?php print $params['id'] ?>').removeAttr('new-menu-id');
				if(no_reload === undefined){
        		mw.reload_module('menu/edit_items');
				}
				
				
        		if(self!==parent && typeof parent.mw === 'object'){
        			parent.mw.reload_module('menu');
        		}
				 menuSelectorInit();
        	});
        }
    }
</script>
<?php $menus = get_menu(); ?>
<?php
 
$menu_name = get_option('menu_name', $params['id']);
 
	if($menu_name  == false and isset( $params['menu_name'])){
 		$menu_name =  $params['menu_name'];
	}elseif($menu_name  == false and isset( $params['name'])){

		$menu_name =  $params['name'];
	} else {


	}





	$active_menu = $menu_name;
  $menu_id = false;
  
 
  
  
  if($menu_id == false and $menu_name != false){
  $menu_id = get_menu('one=1&title='.$menu_name);
	  if($menu_id == false and isset($params['title'])){
	  mw('content')->menu_create('id=0&title=' . $params['title']);
	    $menu_id = get_menu('one=1&title='.$menu_name);
	  }

  }
 if(isset($menu_id['title'])){
	 $active_menu =   $menu_id['title'];
 }
 
 ?>
<?php if(is_array($menus) == true): ?>
<?php if(is_array($menus )): ?>

<div class="control-group form-group">
  <label class="mw-ui-label">
    <?php _e("Select the Menu you want to edit"); ?> 
	
	<small class="right" ><a href="javascript:add_new_menu();" class="mw-ui-label-help mw-ui-small"><?php _e("Create new nenu"); ?> </a> </small>
  </label>
  <div class="mw-ui-select" style="width:100%">
    <select  id="menu_selector_<?php  print $params['id'] ?>" name="menu_name" class="mw_option_field"   type="radio"  onchange="mw.menu_edit_items(this.value, '#items_list_<?php  print $rand ?>');" onblur="mw.menu_edit_items(this.value, '#items_list_<?php  print $rand ?>');" >
      <?php foreach($menus  as $item): ?>
      <?php if($active_menu == false){
		$active_menu =   $item['title'];
	  }?>
      <option <?php  if($menu_name == $item['title'] or $menu_id == $item['id']): ?> <?php  $active_menu = $item['title'] ?> selected="selected" <?php endif; ?> value="<?php print $item['title'] ?>"><?php print ucwords(str_replace('_', ' ', $item['title'])) ?></option>
      <?php endforeach ; ?>
    </select>
	
  </div>
  <hr>
  <label class="mw-ui-label"><?php _e("Select from"); ?>:</label>
  <a href="javascript:requestLink();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green"><span class="ico iplus"></span><span>
  <?php _e("Add Page to Menu"); ?>
  </span></a> <a href="javascript:requestCustomLink();" class="mw-ui-btn mw-ui-btn-medium"><span class="ico iplus"></span><span>
  <?php _e("Add Custom Link"); ?>
  </span></a>
  <hr>
</div>
<?php endif; ?>
<?php else : ?>
<?php _e("You have no exising menus. Please create one."); ?>
<?php endif; ?>
<?php
if(isset($menu_id) and is_array($menu_id) and isset($menu_id['id'])){
  $menu_id = $menu_id['id'];
}

 ?>
<div id="menu-selector" class="mw-ui mw-ui-category-selector mw-tree">
  <microweber module="categories/selector"  for="content" rel_id="<?php print 0 ?>" input-type-categories="radio" input-name-categories="link_id" input-name="link_id"  />
</div>
<div id="custom_link_controller" class="mw-ui-gbox">
  <input type="text" class="mw-ui-field" placeholder="<?php _e("Title"); ?>" name="title" />
  <div class="mw_clear"></div>
  <input type="text" class="mw-ui-field" placeholder="<?php _e("URL"); ?>" name="url"  />
  <input type="hidden" name="parent_id" id="add-custom-link-parent-id" value="<?php  print   $menu_id ?>" />
  <button class="mw-ui-btn2 mw-ui-btn-blue right" onclick="mw.menu_save_new_item('#custom_link_controller');"><?php _e("Add to menu"); ?></button>
</div>
<div class="vSpace"></div>
 
<div class="<?php print $config['module_class']; ?> menu_items order-has-link"   id="items_list_<?php  print $rand ?>">
  <?php if($active_menu != false): ?>
  <h2><?php print $menu_name; ?> Links
    <label class="mw-ui-label"><small><?php _e("Here you can edit your menu links. You can also drag and drop to reorder them."); ?></small></label>
  </h2>
  <label class="mw-ui-label">
    <?php _e("Edit existing links/buttons"); ?>
  </label>
  <div class="vSpace"></div>
  <module data-type="menu/edit_items"  id="items_list_<?php  print $rand ?>" menu-name="<?php  print $active_menu ?>"  />
  <?php endif; ?>
</div>

<div class="vSpace"></div>
