<?php dbg(__FILE__); ?>

<div class="main">

  <?php include "articles_list_subheader.php" ?>

  <?php include "articles_slider_featured.php" ?>
  <?php include ACTIVE_TEMPLATE_DIR."forum_discussions.php" ?>
  <!-- /#popular-discussions -->

  <div class="posts">
    <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item.php" ?>
    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>
    <div class="no-posts"> There is no content here yet! Try again later. </div>
    <?php endif; ?>
  </div>
  <?php //var_dump( ); ?>
  <a class="btn right" style="margin-right: 15px;" href="<?php print $this->taxonomy_model->getUrlForIdAndCache($active_categories[0]);  ?>/view:list"><span>See all</span></a>
  <div class="border-bottom clear">
      <?php require (ACTIVE_TEMPLATE_DIR.'become_a_coach.php') ?></div>
  
    
    
</div>
<!-- /.main -->
<?php include "articles_side_nav.php" ?>
<?php dbg(__FILE__, 1); ?>
