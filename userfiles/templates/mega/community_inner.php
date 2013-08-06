<?php

/*

  type: layout
  content_type: static
  name: Empty
  description: Empty layout

*/

?>

 <?php include "header.php"; ?>
 <?php include "community_header.php"; ?>


 <?php $post = get_content_by_id(POST_ID); ?>



    <div class="container main">

    <div id="in-topic">
        <div id="intopic-title">
          <h4 class="blue pull-left"><?php print $post['title']; ?></h4>
          <a  href="javascript:;"
              class="orangebtn orangebtn-medium pull-left"
              style="margin: 10px 0 0 30px;"
              onclick="mw.tools.scrollTo('.mw-comments-form')">Reply</a>
        </div>

        <div class="post-item">
            <div class="post-item-content">
                <h6 class="blue"><?php $author = get_user_by_id($post['created_by']); print user_name($author['id']); ?> Says:</h6>
                <small class="muted">
                  <?php
                    $date = new DateTime($content['created_on']);
                    print $date->format('F d, Y / H:i');
                  ?>
                </small>
                <div class="post-item-text edit" field="content" rel="content">Add your content</div>

            </div>
        </div>
        <a href="javascript:;" id="replies" name="replies"></a>
        <module type="comments" template="community">

    </div>


    </div>
 <?php include "footer.php"; ?>
