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

    <div class="edit">

    <div class="mw-row">

      <div class="mw-col" style="width: 33.33%">
        <div class="mw-col-container">
            <div class="element mw-ad mw-ad-black">
                <h4>NEW</h4>
                <h1>ARRIVALS</h1>
                <h4>Daily</h4>
            </div>
        </div>

    </div>
    <div class="mw-col" style="width: 33.33%">
        <div class="mw-col-container">
            <div class="element mw-ad mw-ad-blue">
                <h4>NEW</h4>
                <h1>ARRIVALS</h1>
                <h4>Daily</h4>
            </div>
        </div>

    </div>
    <div class="mw-col" style="width: 33.33%">
        <div class="mw-col-container">
            <div class="element mw-ad mw-ad-black">
                <h4>NEW</h4>
                <h1>ARRIVALS</h1>
                <h4>Daily</h4>
            </div>
        </div>

    </div>
    </div>

    </div>




    <module
            data-type="shop/products"
            data-limit="4"
            id="home-products"
            data-description-length="60"
            data-show="thumbnail,title,add_to_cart,description,price"
            data-template="mwcolumns" />

</div>
</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
