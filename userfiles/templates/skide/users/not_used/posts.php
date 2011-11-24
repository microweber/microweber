<div class="main-inner">
  <h2 class="in-content-title in-content-title-ico"> <span>Find <a href="javascript:void(0)">John Charter</a> at:</span> <a class="youtube-ico profile-ico" href="#">Watch in YouTube </a> <a class="twitter-ico profile-ico" href="#">Follow me	on Twitter </a> <a class="facebook-ico profile-ico" href="#">Become a Fan</a> <b class="titleleft"></b> </h2>
  <div class="pad">
    <h2 class="title">From My Blog</h2>
    <br />
<br />

     <?php include ACTIVE_TEMPLATE_DIR. "articles_search_bar.php" ?>
    <br />
    <br />

    <?php if(!empty($posts_data['posts'])): ?>
    <?php foreach ($posts_data['posts'] as $the_post): ?>
    <?php $show_edit_and_delete_buttons = true; ?>
    <?php include ACTIVE_TEMPLATE_DIR."articles_list_single_post_item.php" ?>
    <?php endforeach; ?>
    
    
     <?php include ACTIVE_TEMPLATE_DIR."articles_paging.php" ?>
    
    
    
    
    
    <?php else : ?>
    <div class="post">
      <p> There are no posts here. Try again later. </p>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php include "posts_sidebar.php"  ?>
