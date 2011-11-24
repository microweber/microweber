<?php dbg(__FILE__); ?>

<div class="main" id="bloglist">
<?php include "comunity_nav.php" ?>
  <!-- /#popular-discussions -->
  <div class="posts">
    <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php $no_author_info = true; ?>
    <?php $article_list_no_type = true; ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item.php" ?>
    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>
    <div class="post">
      <p> There are no posts here. Try again later. </p>
    </div>
    <?php endif; ?>
  </div>
 
</div>
<!-- /.main -->
<?php include "blog_side_nav.php" ?>
<?php dbg(__FILE__, 1); ?>
