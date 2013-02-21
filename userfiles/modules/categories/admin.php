 
<h1>Select parent page</h1>
<? //$rand = uniqid(); ?>
<? $pages = get_content('content_type=page&subtype=dynamic&limit=1000');   ?>
<?php $posts_parent_page =  get_option('data-content-id', $params['id']); ?>
<strong>Select parent page</strong>
<select name="data-content-id"     class="mw_option_field"  >
  <option  valie="0"   <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
  <?  foreach($pages as $item):	 ?>
  <option value="<? print $item['id'] ?>"   <? if(($item['id'] == $posts_parent_page)): ?>   selected="selected"  <? endif; ?>     > <? print $item['title'] ?> </option>
  <? endforeach ?>