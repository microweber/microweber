<?php
$short_profile  = true;
// include "articles_read_top_profile_box.php" ?>
   <?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<div class="c border-bottom">&nbsp;</div>

<div class="main" style="padding-top: 0">



<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>



  <div class="posts trainning-posts">
    <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    

    <?php 
	$no_author_no_votes = true;
	$products_page = true;
	$no_categorie=true;
	$no_learning_center_tabs=true;
	$no_popular_profiles=true;
	include "articles_list_single_post_item.php" ?>
    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>
    <div class="post">
      <p> There are no   <?php print $type = $this->core_model->getParamFromURL ( 'type' ); ?> here. Try again later. </p>
    </div>
    <?php endif; ?>
  </div>
</div>
<!-- /.main -->
<?php include "articles_side_nav.php" ?>

