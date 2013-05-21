<?php $menus = get_menu(); ?>
    <?php //$rand = uniqid(); ?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Add to Navigation"); ?>
  </label>
  <div class="relative">
    <div class="mw-ui-field mw-tag-selector mw-selected-menus" id="mw-selected-menus-{rand}" style="width: 605px;">
        <input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to navigation"); ?>" />
    </div>
    <?php
$content_id = false;
 if(isset($params['content_id'])){
	 $content_id = $params['content_id'];
 }

$menu_name = false;
 if(isarr($menus )): ?>
    <ul id="mw-menu-selector-list-{rand}" class="mw-menu-selector-list">
      <?php foreach($menus  as $item): ?>
      <li>
          <label class="mw-ui-check">
            <input id="menuid-<?php print $item['id'] ?>" name="add_content_to_menu[]"  <?php if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <?php endif; ?> value="<?php print $item['id'] ?>" type="checkbox">
            <span></span><span class="mw-menuselector-menu-title"><?php print ucwords(string_nice($item['title'])) ?></span>
          </label>
      </li>
      <?php endforeach ; ?>
    </ul>
    <?php endif; ?>
  </div>
  <script>

          $(document).ready(function(){
              mw.tools.tag({
                  tagholder:'#mw-selected-menus-{rand}',
                  items: ".mw-ui-check",
                  itemsWrapper: mwd.getElementById('mw-menu-selector-list-{rand}'),
                  method:'prepend'
              });
          });

  </script>
</div>