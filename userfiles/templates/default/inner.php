<?php

/*

type: layout
content_type: post
name: Post inner layout

description: Post inner layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
<div class="container"> 
  <!--=========== Blog ===========-->
  <div class="row"> 
    <!-------------- Blog post -------------->
    <div class="span8">
      <h2  class="edit"  rel="content"  data-field="title">Your title goes here</h2>
      <module data-type="pictures" data-content-id="<? print CONTENT_ID; ?>" template="slider" />
      <div class="blog-post-body edit"  rel="content"  data-field="content">
        <p>Your content goes here</p>
      </div>
    </div>
    <div class="span4">
        <? include TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
  </div>
</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
