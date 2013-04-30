<? //include_once($config['path_to_module'].'functions.php'); ?>
<? // d($params);

  $rand = crc32(serialize($params));
  ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">My menus</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li><a href="javascript:;" id="add_new_menu_tab"></a></li>
  </ul>
  <a href="javascript:add_new_menu();" class="mw-ui-btn mw-ui-btn-green" style="height: 19px;position: absolute;right: 13px;top: 12px;z-index: 1"><span class="ico iplus"></span><span>Create New Menu</span></a>
  <div class="tab">
    <? include($config['path_to_module'].'admin_live_edit_tab1.php');   ?>
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab">
    <div id="add_new_menu" style="overflow: hidden">
      <input name="menu_id"  type="hidden"  value="0"    />
      <div style="overflow: hidden">
        <input class="left mw-ui-field" style="width:300px" type="text" name="title" placeholder="<?php _e("Menu Name"); ?>" />
        <button type="button" class="mw-ui-btn2 right" onclick="mw.menu_save('#add_new_menu')">Add</button>
      </div>
    </div>
  </div>
</div>
