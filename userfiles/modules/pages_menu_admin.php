<h1>Pages tree admin</h1>
<? $rand = uniqid(); ?>
<? $pages = get_content('content_type=page&limit=1000');   ?>
<?php $posts_parent_page =  option_get('data-parent', $params['id']); ?>
<strong>From page</strong>
<select name="data-parent"     class="mw_option_field"  >
  <option  valie="0"   <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
  <?  foreach($pages as $item):	 ?>
  <option value="<? print $item['id'] ?>"   <? if(($item['id'] == $posts_parent_page)): ?>   selected="selected"  <? endif; ?>     > <? print $item['title'] ?> </option>
  <? endforeach; ?>
</select>
<?php $include_categories =  option_get('data-include_categories', $params['id']); ?>
<strong>include_categories</strong>
<select name="data-include_categories"     class="mw_option_field"  >
  <option  value="0"   <? if((0 == intval($include_categories))): ?>   selected="selected"  <? endif; ?>>No</option>
  <option  value="1"   <? if((1 == intval($include_categories))): ?>   selected="selected"  <? endif; ?>>Yes</option>
</select>
