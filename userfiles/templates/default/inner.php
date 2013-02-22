<?php

/*

type: layout
content_type: post
name: Post inner layout

description: Post inner layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>


<div class="container">
  <!--=========== Blog ===========-->
  <div class="row">
    <!-------------- Blog post -------------->
    <div class="span8">
      <h2  class="edit"  rel="content" field="title">Your title goes here</h2>

      <div class="blog-post-body edit"  rel="content" field="content">
        <p>Your content goes here</p>
      </div>
       <module data-type="pictures" data-content-id="<? print CONTENT_ID; ?>" template="bootstrap_carousel" />
       <module data-type="comments" data-template="default" data-content-id="<? print CONTENT_ID; ?>"  />
    </div>
    <div class="span4">
        <? include TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
  </div>
</div>

<? include   TEMPLATE_DIR.  "footer.php"; ?>
