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

    <div class="container main">
        <?php  $url_cat = url_param('category');
        if(is_string($url_cat)){  ?>
            <h4>Discussions for <strong><?php  $cat = get_category_by_id($url_cat); print $cat['title']; ?></strong></h4>
            <?php
            $posts = get_content('category='.$url_cat);
            if(is_array($posts)){
              foreach($posts as $post){     ?>
              <div class="bbox community-single-post">
                <div class="bbox-content">
                   <h5><a class="blue" href="<?php print $post['url']; ?>"><?php print $post['title']; ?></a>
                   <a href="<?php print $post['url']; ?>#replies" class="pull-right">
                     <?php
                        $data = array( 'content_id' => $post['id'] );
                        $comments = get_comments( $data );
                        if(is_array($comments)){
                          print sizeof( $comments );
                        }
                        else{ print 0; }
                      ?>
                     <i class="icon-comment"></i>
                    </a>
                    </h5>
                </div>
              </div>
          <?php    }    }  else {    ?>
                <div class="bbox">
                  <div class="bbox-content">
                      No topics for <strong><?php  print $cat['title']; ?></strong>,
                      &nbsp;&nbsp;&nbsp;<a href="" class="blue"><em class="icon-double-angle-left"></em>&nbsp;Go back</a>
                  </div>
                </div>
       <?php   }   ?>

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
 <?php include "footer.php"; ?>
