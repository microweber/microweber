<h1>Pages tree admin</h1>
<?php //$rand = uniqid(); ?>
<?php $pages = get_content('content_type=page&limit=1000');   ?>
<?php $posts_parent_page =  get_option('data-parent', $params['id']); ?>
<strong>From page</strong>
<select name="data-parent"     class="mw_option_field"  >
  <option  valie="0"   <?php if((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>None</option>
  <?php  foreach($pages as $item):	 ?>
  <option value="<?php print $item['id'] ?>"   <?php if(($item['id'] == $posts_parent_page)): ?>   selected="selected"  <?php endif; ?>     > <?php print $item['title'] ?> </option>
  <?php endforeach; ?>
</select>
<?php $include_categories =  get_option('data-include_categories', $params['id']); ?>
<strong>include_categories</strong>
<select name="data-include_categories"     class="mw_option_field"  >
  <option  value="0"   <?php if((0 == intval($include_categories))): ?>   selected="selected"  <?php endif; ?>>No</option>
  <option  value="1"   <?php if((1 == intval($include_categories))): ?>   selected="selected"  <?php endif; ?>>Yes</option>
</select>



    <module type="admin/modules/templates"  />
