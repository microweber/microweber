
<div class="sidebar">


    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>


  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_search.php') ?>
  <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_category_tree.php') ?>

  
 
  
  <span class="c"></span>
  <?php if($no_popular_profiles  == false): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'profiles_popular_side_bar_box.php') ?>
  <?php endif; ?>
  <br />
  <a href="#" class="ask-a-question">Ask a Question. Feel free to ask us</a>
  <div class="tweet-wrap border-top"> <a href="#" class="tweet facebook-sidebar">Become a fan</a> <a href="#" class="tweet twitter-sidebar">Follow us</a> </div>
</div>
<!-- /.sidebar -->