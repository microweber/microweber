<?php

/*

type: layout
content_type: post
name: Post inner layout

description: Post inner layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>


<div id="content">
  <div class="container">
    <div class="row">
      <div class="span8">
         <div class="post-single-inner">
            <h2  class="edit title"  rel="content" field="title">Your title goes here</h2>
            <div class="edit post-content"  rel="content" field="content">
              <p>Your content goes here</p>
            </div>
         </div>
         <div class="clearfix post-gallery">
              <module data-type="pictures" data-content-id="<? print CONTENT_ID; ?>" template="bootstrap_carousel" />
         </div>
         <hr>
         <div class="clearfix post-comments">
              <module data-type="comments" data-template="default" data-content-id="<? print CONTENT_ID; ?>"  />
         </div>
      </div>
      <div class="span3 offset1">
          <? include TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
        </div>
    </div>
  </div>
</div>

<? include   TEMPLATE_DIR.  "footer.php"; ?>
