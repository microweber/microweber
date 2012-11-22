<? $rand = uniqid(); ?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('url.js');
 </script>
<script type="text/javascript">
  mw.menu_save = function($selector){
   
      var obj = mw.form.serialize($selector);
      $.post("<? print site_url('api/add_new_menu') ?>",  obj, function(data){
        mw.reload_module('<? print $params['type']; ?>');
      });
 
 }
 
 
  mw.menu_edit_items = function($menu_id, $selector){
  		mw.$($selector).attr('data-menu-id',$menu_id);
  	 mw.load_module('nav/edit_items',$selector);
      
 
 }
 </script>
 
 
<? $menus = get_menu(); ?>

<h2>Tab 1 - settings</h2>
<fieldset id="add_new_menu">
  <div class="control-group">
    <label class="control-label">Create new menu</label>
    <input name="menu_id"  type="hidden"  value="0"    />
    <div class="controls">
      <input type="text" name="title" value="Menu name" onFocus="if(this.value=='Menu name')this.value='';">
    </div>
    <button type="button" class="mw-ui-btn" onclick="mw.menu_save('#add_new_menu')">Save</button>
  </div>
</fieldset>
<?php $menu_name = option_get('menu_name', $params['id']);
$menu_id = false;
if($menu_name != false){
$menu_id = get_menu_id('title='.$menu_name);	
}
 
 ?>
<? if(isarr($menus) == true): ?>
<? if(isarr($menus )): ?>
<fieldset>
  <div class="control-group">
    <label class="control-label">Use existing menu</label>
    <? foreach($menus  as $item): ?>
    <div class="controls">
      <label>
        <input name="menu_name" class="mw_option_field"   type="radio" data-refresh="nav"  value="<? print $item['title'] ?>" <? if($menu_name == $item['title']): ?> checked="checked" <? endif; ?> />
        <? print $item['title'] ?></label><a href="javascript:mw.menu_edit_items(<? print $item['id'] ?>, '#items_list_<?  print $rand ?>');"> | Edit menu items</a>
    </div>
    <? endforeach ; ?>
  </div>
</fieldset>
<? endif; ?>
<? else : ?>
You have no exising menus. Please create one.
<? endif; ?>
<div class="<? print $config['module_class']; ?> menu_items" id="items_list_<?  print $rand ?>"></div>
<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
