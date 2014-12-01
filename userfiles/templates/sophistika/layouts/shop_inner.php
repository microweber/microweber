<?php include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="box-container">
      <module type="breadcrumb" />
      <div class="edit" field="content" rel="post">
        <div class="mw-row shop-product-row">
          <div class="mw-col" style="width:65%">
            <div class="mw-col-container">
              <module type="pictures" rel="content" template="product_gallery_multiline" />
            </div>
          </div>
          <div class="mw-col" style="width:35%">
            <div class="mw-col-container">
              <div class="product-description">
                 <div class="edit"  field="content_body" rel="post">
                 <h2>Product inner page</h2>
                 <p class="element">
                    This text is set by default and it is suitable for edit in real time.
                    By default the drag and drop core feature will allow you to position it anywhere on the site.
                    Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.
                 </p>
                </div>
                <module type="shop/cart_add" rel="post" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="inner-bottom-box">
    <div class="container">
      <div class="box-container">
        <div class="edit"  field="related_products" rel="inherit">
          <div class="latest-items">

            <h2 class="section-title element">Related Products</h2>
            <hr class="hr1" style="margin-bottom: 30px;">
            <module type="shop/products" related="true" limit="3" ajax-paging="true" data-show="thumbnail,title,add_to_cart,price" />
            <p class="element">&nbsp;</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include TEMPLATE_DIR. "footer.php"; ?>
