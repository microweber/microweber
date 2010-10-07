<?php dbg(__FILE__); ?>
<div class="sidebar">
 <div id="people_online">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>
  </div>

<div class="order-search">
   <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_search.php') ?>
</div>
   <span class="c"></span>   <br />
  <br /><span class="c"></span>


  <form id="demo_side_form" action="" method="post">
  
  </form>
  <br />
  <br />
  <?php if($no_categorie  == false): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_category_tree.php') ?>
  <?php endif; ?>

  <?php if($no_learning_center_tabs  == false): ?>
  <div style="width:302px;margin:0 auto">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
  </div>
  <?php endif; ?>
  <span class="c"></span>
  <?php if($no_popular_profiles  == false): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'profiles_popular_side_bar_box.php') ?>
  <?php endif; ?>
  <br />
  <a href="#" class="ask-a-question">Ask a Question. Feel free to ask us</a>
  <div class="tweet-wrap border-top"> <a href="#" class="tweet facebook-sidebar">Become a fan</a> <a href="#" class="tweet twitter-sidebar">Follow us</a> </div>
</div>
<?php dbg(__FILE__, 1); ?>