<? $menus = get_menu(); ?>
<?php $menu_name = option_get('menu_name', $params['id']) ?>
<h2>Tab 1 - settings</h2>
<fieldset>
  <div class="control-group">
    <label class="control-label">Create new menu</label>
    <input name="id"  type="hidden"  value="0"    />
    <div class="controls">
      <input type="text" name="controls" value="Menu name" onFocus="if(this.value=='Menu name')this.value='';">
    </div>
  </div>
</fieldset>
<? if(isarr($menus) == true): ?>
<? if(isarr($menus )): ?>
<fieldset>
  
  <div class="control-group">
    <label class="control-label">Use existing menu</label>
    <? foreach($menus  as $item): ?>
    <div class="controls">
      <label>
        <input name="menu_name" class="mw_option_field"   type="radio" data-refresh="nav"  value="<? print $item['title'] ?>" <? if($menu_name == $item['title']): ?> checked="checked" <? endif; ?>
 />
        <? print $item['title'] ?> </label>
    </div>
    <? endforeach ; ?>
  </div>
</fieldset>
<? endif; ?>
<? else : ?>
<? endif; ?>
<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
