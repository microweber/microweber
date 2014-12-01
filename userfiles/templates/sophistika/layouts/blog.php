<?php

/*

  type: layout
  content_type: dynamic
  name: Blog
  position: 5
  description: Blog
  tag: blog

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="container">
  <div class="box-container">
    <div class="row">
      <div class="col-md-9">
            <module content-id="<?php print PAGE_ID; ?>" type="posts" template="sophistika" data-description-length="400" />
      </div>
      <div class="col-md-3" id="blog-sidebar">
        <?php include "blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
