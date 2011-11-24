 <?php dbg(__FILE__); ?>
<div class="main equal">
  <div id="home-title">
    <h2>Learn and Earn... Keep Going, Keep Growing!</h2>
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
  </div>
  <div id="home-flash"> 
    <!-- /Flash goes here --> 
  </div>
  <div id="home-tops-slider-wrapper">
    <h2 class="title">Welcome to your <strong>Online Business</strong> Social Network.</h2>
    <?php require (ACTIVE_TEMPLATE_DIR.'home_slider.php') ?>
  </div>
  <!-- /#home-tops-slider-wrapper -->
  <?php include ACTIVE_TEMPLATE_DIR."forum_discussions.php" ?>
  
  <!-- /.main -->
  
  <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  <div class="content-title">
    <h2 class="title left">Featured Articles</h2>
  </div>
  <?php require (ACTIVE_TEMPLATE_DIR.'articles_slider_featured.php') ?>
  
  <?php require (ACTIVE_TEMPLATE_DIR.'become_a_coach.php') ?>
  
  
  
  

</div>
<!-- /.main -->
<div class="sidebar border-left">
  <div id="side-add-area">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>


    <object width="301" height="253">
    <param name="movie" value="<?php print site_url(); ?>flash/sideadd/home_add.swf" />
    <param name="flashvars" value="dataPath=<?php print site_url(); ?>flash/sideadd/data.php?url=<?php print base64_encode(site_url()) ?>&url2=<?php print base64_encode(TEMPLATE_URL) ?>" />
    <param name="wmode" value="transparent" />
    <param name="menu" value="false" />
    <embed menu="false" src="<?php print site_url(); ?>flash/sideadd/home_add.swf" flashvars="dataPath=<?php print site_url(); ?>flash/sideadd/data.php?url=<?php print base64_encode(site_url()) ?>&url2=<?php print base64_encode(TEMPLATE_URL) ?>" wmode="transparent" type="application/x-shockwave-flash" width="301" height="253" />
</object>

<div class="c" style="padding-bottom: 20px;">&nbsp;</div>
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_search.php') ?>











      <!--<a href="#"><img src="<?php print TEMPLATE_URL; ?>img/side_add.jpg" alt="" /></a>--> 
  </div>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
  <?php $no_profiles_popular_title= true;
  require (ACTIVE_TEMPLATE_DIR.'profiles_popular_side_bar_box.php') ?>
  <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
  <a href="#" class="ask-a-question">Ask a Question. Feel free to ask us</a>
  <div class="tweet-wrap border-top"> <a href="#" class="tweet facebook-sidebar">Become a fan</a> <a href="#" class="tweet twitter-sidebar">Follow us</a> </div>
</div>
<!-- /.sidebar --> 

 <?php dbg(__FILE__, 1 ); ?>