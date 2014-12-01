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
<?php include TEMPLATE_DIR. "header.php"; ?>





<div class="edit" field="content" rel="content">

<div class="container">
   <div class="box-container">
    <h2 class="edit page-title" field="title" rel="content">Hot Concept Art</h2>
    <div class="masonry-gallery">
      <module content-id="<?php print PAGE_ID; ?>" type="pictures" template="masonry" />
    </div>
  </div>

</div>

</div>

<?php include TEMPLATE_DIR. "footer.php"; ?>