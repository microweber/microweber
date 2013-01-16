<? $menus = get_menu(); ?>
    <? $rand = uniqid(); ?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Add to Navigation"); ?>
  </label>
  <div class="relative">
    <div class="mw-ui-field mw-tag-selector mw-selected-menus" id="mw-selected-menus-<?php print $rand; ?>" style="width: 605px;">
        <input type="text" class="mw-ui-invisible-field" data-default="Click here to add to navigation" value="Click here to add to navigation" />
    </div>
    <?
$content_id = false;
 if(isset($params['content_id'])){
	 $content_id = $params['content_id'];
 }

$menu_name = false;
 if(isarr($menus )): ?>
    <ul id="mw-menu-selector-list-<?php print $rand; ?>" class="mw-menu-selector-list">
      <? foreach($menus  as $item): ?>
      <li>
          <label class="mw-ui-check">
            <input id="menuid-<? print $item['id'] ?>" name="add_content_to_menu[]"  <? if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <? endif; ?> value="<? print $item['id'] ?>" type="checkbox">
            <span></span><span class="mw-menuselector-menu-title"><? print ucwords(string_nice($item['title'])) ?></span>
          </label>
      </li>
      <? endforeach ; ?>
    </ul>
    <? endif; ?>
  </div>
  <script>

          $(document).ready(function(){
              mw.tools.tag({
                  tagholder:'#mw-selected-menus-<?php print $rand; ?>',
                  items: ".mw-ui-check",
                  itemsWrapper: mwd.getElementById('mw-menu-selector-list-<?php print $rand; ?>'),
                  method:'prepend'
              });

          });

  </script>
</div>