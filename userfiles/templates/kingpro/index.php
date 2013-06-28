<?php

/*

  type: layout
  content_type: static
  name: Home
  description: Home layout

*/

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div class="container">

    <div id="home-slider" class="element"><module type="pictures" content-id="<?php print PAGE_ID; ?>" template="slider"></div>


    <module
            data-type="shop/products"
            data-limit="4"
            id="home-products"
            data-description-length="60"
            data-show="thumbnail,title,add_to_cart,description,price"
            data-template="mwcolumns" />

</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
