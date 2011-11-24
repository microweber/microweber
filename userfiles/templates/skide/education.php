<div class="wrap">
  <div id="main_content">
    <div id="user_sidebar">
      <h3 class="user_sidebar_title nomargin">Educatiuon Categories</h3>
      <? $categories = get_categories(); ?>
      <ul class="user_side_nav user_side_nav_nobg">
        <? foreach($categories as $category): ?>
        <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
        <? endforeach; ?>
      </ul>
      <h3 class="user_sidebar_title">Questions and answers</h3>
      <ul class="user_side_nav user_side_nav_nobg">
        <li><a href="#">Post question</a></li>
        <li><a href="#">Questions and answers</a></li>
      </ul>
    </div>
    <!-- /#user_sidebar -->
    <div id="main_side">
      <? if(!empty($post)): ?>
      <? include(TEMPLATE_DIR.'education_inner.php')	; ?>
      <? else : ?>
      <h2>Education/Article</h2>
      
      
      
       
      
      
       <microweber module="posts/list" file="posts_list_wide" category="<? print  $category['id'] ?>" limit="3">
      
      
      
      <br />
      <?   
$params= array();
$params['without_custom_fields']=false; 
$the_posts = get_posts($params);
 
?>
      <?
$i = 0;
foreach($the_posts as $the_post):
include(TEMPLATE_DIR.'post_item_wide.php')	;?>
      <? if($i == 1): ?>
 
      <div class="post_list" align="center">  <? include(TEMPLATE_DIR.'banner_wide.php')	; ?></div>
      <? endif; ?>
      <? $i++; endforeach; ?>
      <br />
      <br />
      <? paging($display = 'divs') ?>
      <? endif; ?>
    </div>
  </div>
</div>
