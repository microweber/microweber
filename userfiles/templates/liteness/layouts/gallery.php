<?php

/*

  type: layout
  content_type: static
  name: Gallery
  position: 3
  description: Gallery
  tag:home

*/

?>
<?php include TEMPLATE_DIR . "header.php"; ?>
<div class="edit" rel="content" field="liteness_content">
    <div class="container">
        <div class="box-container">
            <h2 class="page-title">Gallery</h2>
            <div class="masonry-gallery">
                <module content-id="<?php print PAGE_ID; ?>" type="pictures" template="pictures_grid"/>
            </div>
        </div>
    </div>
</div>
<?php include TEMPLATE_DIR . "footer.php"; ?>
