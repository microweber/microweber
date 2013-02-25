<? //include_once($config['path_to_module'].'functions.php'); ?>
<? // d($params);

  $rand = crc32(serialize($params));
  ?>


  <style type="text/css">

  .mw_tabs_layout_simple .mw_simple_tabs_nav li{
    margin:0 11px;
  }
  .mw-ui-category-selector{
    width: auto;
  }
  #custom_link_controller {
    width: 280px;
    display: none;
  }

  #custom_link_controller input[type='text']{
    width: 220px;
    margin-bottom: 20px;
  }

  </style>

<script  type="text/javascript">
  mw.require('forms.js', true);
  mw.require('url.js', true);
</script>
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
   var m = mw.$("#add_new_menu")
   m.toggle();
   if(m.is(":visible")){
       mw.$("#requestlink_holder").hide()
   }
 }


  mw.menu_edit_items = function($menu_id, $selector){


  mw.$($selector).attr('menu-name',$menu_id);
   mw.load_module('nav/edit_items',$selector);


 }


$(document).ready(function(){

 mw.treeRenderer.appendUI('#menu-selector');

   mw.$('#menu-selector input[type="radio"]').commuter(function(){

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
      mw.$("#menu-selector").hide();
   });


});

 </script>
<? $menus = get_menu(); ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">

  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">My menus</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>


  <a href="javascript:add_new_menu();" class="mw-ui-btn" style="height: 19px;position: absolute;right: 13px;top: 12px;z-index: 1"><span class="ico iplus"></span><span>Create New Menu</span></a>


  <div class="tab">






    <div id="add_new_menu" style="display: none;overflow: hidden">


        <input name="menu_id"  type="hidden"  value="0"    />
        <div style="overflow: hidden">
          <input class="left" type="text" name="title" value="Menu Name" data-default="Menu Name" onfocus="mw.form.dstatic(event);" onblur="mw.form.dstatic(event);" />

          <button type="button" class="mw-ui-btn right" onclick="mw.menu_save('#add_new_menu')">Add</button>

        </div>
      <hr />
    </div>

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
        <label class="mw-ui-label">Use existing menu</label>

        <div class="mw-ui-select" style="width:100%">
          <select  name="menu_name" class="mw_option_field"   type="radio" data-refresh="nav" onchange="mw.menu_edit_items(this.value, '#items_list_<?  print $rand ?>');" >
            <? foreach($menus  as $item): ?>
            <option <?  if($menu_name == $item['title']): ?> <?  $active_menu = $item['title'] ?> selected="selected" <? endif; ?> value="<? print $item['title'] ?>"><? print $item['title'] ?></option>
            <? endforeach ; ?>
          </select>
        </div>
          <div class="vSpace"></div>
        <a href="javascript:requestLink();" class="mw-ui-btn" style="height: 19px;"><span class="ico iplus"></span><span>New Link</span></a>
        <a href="javascript:requestCustomLink();" class="mw-ui-btn" style="height: 19px;"><span class="ico iplus"></span><span>Custom Link</span></a>
         <div class="vSpace"></div>
         <div id="requestlink_holder" style="display: none;"></div>
         <div class="vSpace"></div>
      </div>

    <? endif; ?>
    <? else : ?>
    You have no exising menus. Please create one.
    <? endif; ?>

    <div id="menu-selector" class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector">

    <microweber module="categories/selector"  for="content" to_table_id="<? print 0 ?>" input-type-categories="radio" input-type-categories="radio" input-name-categories="link_id" input-name="link_id"  />

    </div>


 <div id="custom_link_controller" class="mw-ui-gbox">
    <input type="text" placeholder="<?php _e("Title"); ?>" name="title" />
    <input type="text" placeholder="<?php _e("URL"); ?>" name="url"  />
    <input type="hidden" name="parent_id" value="<?  print   $menu_id ?>" />
    <button class="mw-ui-btn mw-ui-btn-blue right" onclick="mw.menu_save_new_item('#custom_link_controller');">Add to menu</button>
</div>

    <?  //d( $active_menu); ?>
    <? //.. d( $params); ?>
    <div class="<? print $config['module_class']; ?> menu_items"   id="items_list_<?  print $rand ?>">
      <? if($active_menu != false): ?>
      <module data-type="nav/edit_items"  menu-name="<?  print $active_menu ?>"  />
      <? endif; ?>
    </div>
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
</div>