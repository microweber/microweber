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
        <?php /*<div class="content-header">
          <h2 class="edit page-title" field="title" rel="page">Page Title</h2>
        </div>*/ ?>

        <div class="edit" field="sub-content" rel="page">
            <module content-id="<?php print PAGE_ID; ?>" type="posts" template="clean" data-show="thumbnail,title,description,read_more" description-length="220"  />
        </div>
      </div>
      <div class="col-md-3" id="blog-sidebar">
        <?php include "blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
