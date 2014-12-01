<?php

/*

  type: layout
  content_type: dynamic
  name: Blog
  position: 5
  description: Blog
  tag:Blog

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>
<div class="container">
  <div class="box-container">
    <div class="row">
      <div class="col-md-12">


          <module content-id="<?php print PAGE_ID; ?>" type="posts" template="clean" data-show="thumbnail,title,description" />

      </div>

      <?php /*<div class="col-md-3" id="blog-sidebar">
        <?php include "blog_sidebar.php"; ?>
      </div>*/ ?>
    </div>
  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>