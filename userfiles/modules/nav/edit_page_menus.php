<? $menus = get_menu(); ?>
<?
$content_id = false;
 if(isset($params['content_id'])){
	 $content_id = $params['content_id'];
 }
$menu_name = false;
 if(isarr($menus )): ?>

<div class="control-group-nav">
  <label class="control-label">Add to navigation</label>
  <label>
    <input name="add_content_to_menu[]"   type="radio"    value="remove_from_all"    />
    Remove from all menus</label>
  <? foreach($menus  as $item): ?>
  <div class="controls">
    <label>
      <input name="add_content_to_menu[]"   type="checkbox"    value="<? print $item['id'] ?>" <? if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <? endif; ?> />
      <? print $item['title'] ?> </label>
  </div>
  <? endforeach ; ?>
</div>
<? endif; ?>
 
