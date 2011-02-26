<div id="user_sidebar">
  <? $categories = get_categories(); ?>
  <h3 class="user_sidebar_title nomargin">Games by category</h3>
  <ul class="user_side_nav user_side_nav_nobg">
    <? foreach($categories as $category): ?>
    <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
    <? endforeach; ?>
  </ul>
</div>
<!-- /#user_sidebar -->
