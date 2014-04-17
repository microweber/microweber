<?php

/*

  type: layout
  content_type: static
  name: Blog
  position: 5
  description: Blog
  tag:home

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>
<div class="container">
  <div class="box-container">
    <div class="row">
      <div class="col-md-9">
      
      
      
        <div class="content-header">
          <h2 class="edit page-title" field="title" rel="page">Page Title</h2>
        </div>
        
        <div class="edit" field="content" rel="page"></div>
        
        
        <div class="masonry-gallery">
          <module content-id="<?php print PAGE_ID; ?>" type="posts" />
        </div>
      </div>
      <div class="col-md-3" id="blog-sidebar">
        <?php include "blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>