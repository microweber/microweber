<? //include_once($config['path_to_module'].'functions.php'); ?>
<? // d($params);

  $rand = crc32(serialize($params));
  ?>


  <style type="text/css">

  .mw_tabs_layout_simple .mw_simple_tabs_nav li{
    margin:0 11px;
  }

  </style>

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


 requestlink = function(){
   if(typeof _requestlink === 'undefined'){
        _requestlink = true;
        var url = mw.external_tool("rte_link_editor");
        mw.$("#requestlink_holder").show().html("<iframe style='border:1px solid #D7D7D7;margin-left:-20px' frameBorder='0' width='430' height='300' src='" + url +"' ></iframe>");
   }
   else{
        mw.$("#requestlink_holder").toggle();
   }


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
        <a href="javascript:requestlink();" class="mw-ui-btn" style="height: 19px;"><span class="ico iplus"></span><span>New Link</span></a>
         <div class="vSpace"></div>
         <div id="requestlink_holder" style="display: none;"></div>
         <div class="vSpace"></div>
      </div>

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