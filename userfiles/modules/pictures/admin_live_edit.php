
 

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">My menus</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">
 <? include_once($config['path_to_module'].'admin_backend.php'); ?>
  </div>
  <div class="tab">
 <strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
<microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
 
  </div>
</div>