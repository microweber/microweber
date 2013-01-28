<? //include_once($config['path_to_module'].'functions.php'); ?>
<? // d($params);
 
 //$rand = crc32(serialize($params));
  ?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('url.js');
  
  </script>
<script type="text/javascript">
  mw.menu_save = function($selector){
   
      var obj = mw.form.serialize($selector);
      $.post("<? print site_url('api/add_new_menu') ?>",  obj, function(data){
      //  mw.reload_module('nav');
	  window.location.href = window.location.href;
      });
  
 }
 
 
  mw.menu_edit_items = function($menu_id, $selector){
  
	  
  mw.$($selector).attr('menu-name',$menu_id);
   mw.load_module('nav/edit_items',$selector);
      
 
 }
 </script>
<? $menus = get_menu(); ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">My menus</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">
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
    <fieldset>
      <div class="control-group">
        <label class="control-label">Use existing menu</label>
        <select  name="menu_name" class="mw_option_field"   type="radio" data-refresh="nav" onchange="mw.menu_edit_items(this.value, '#items_list_<?  print $rand ?>');" >
          <? foreach($menus  as $item): ?>
          <option <?  if($menu_name == $item['title']): ?> <?  $active_menu = $item['title'] ?> selected="selected" <? endif; ?> value="<? print $item['title'] ?>"><? print $item['title'] ?></option>
          <? endforeach ; ?>
        </select>
      </div>
    </fieldset>
    <? endif; ?>
    <? else : ?>
    You have no exising menus. Please create one.
    <? endif; ?>
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