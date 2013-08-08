<?php

/*

  type: layout
  content_type: static
  name: Community
  description: Community layout

*/

?>

 <?php include "header.php"; ?>
 <?php include "community_header.php"; ?>


 <?php if (!isset($_REQUEST['new-topic'])):?>

    <div class="container main">
        <?php  $url_cat = url_param('category');
        if(is_string($url_cat)){  ?>
            <h4>Discussions for <strong><?php  $cat = get_category_by_id($url_cat); print $cat['title']; ?></strong></h4>
            <module type="posts" template="forum" />

         <?php } else { ?>
             <h4>Main categories of discusions</h4>
        <?php
             $cats = get_categories('rel=content&rel_id='.PAGE_ID);
             if(is_array($cats)){
               foreach($cats as $cat){    ?>
               <a class="community-cat" href="<?php print category_link($cat['id']); ?>">
                  <span><?php print $cat['title']; ?></span>
                  <div class="pull-right">
                  <?php $posts = get_posts('category='.$cat['id']); ?>
                      <strong><?php if(is_array($posts)){print sizeof($posts);} else{print 0;} ?></strong><small>Opened Discusions</small>
                  </div>
              </a>
      <?php  }  } }  ?>

    </div>
 <?php  else: ?>
 <?php include "community_new_topic.php"; ?>
 <?php  endif; ?>

 <?php include "footer.php"; ?>
