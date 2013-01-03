<? $menus = get_menu(); ?>
<?
$content_id = false;
 if(isset($params['content_id'])){
	 $content_id = $params['content_id'];
 }
$menu_name = false;
 if(isarr($menus )): ?>

<div class="control-group-nav">
  <label class="mw-ui-label">Add to navigation</label>

    <label class="mw-ui-check semi_hidden"><input name="add_content_to_menu[]"  type="radio" value="remove_from_all" /><span></span>
        <span>Remove from all menus</span>
    </label>
  <? foreach($menus  as $item): ?>
  <div class="controls">
    <label class="mw-ui-check">
      <input name="add_content_to_menu[]"   type="checkbox"    value="<? print $item['id'] ?>" <? if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <? endif; ?> />
      <span></span>
      <span><? print $item['title'] ?></span></label>
  </div>
  <? endforeach ; ?>
</div>
<div class="vSpace"></div>
<? endif; ?>

