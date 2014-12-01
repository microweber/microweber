<?php include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="box-container">
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
                <h2 class="edit product-title" field="title" rel="post">Product inner page</h2>
                <div class="edit"  field="content_body" rel="post">
                  <p class="element">This text is set by default and it is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.</p>
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
            <module type="shop/products" related="true" limit="3" ajax-paging="true" hide-paging="true" data-show="thumbnail,title" />
            <p class="element">&nbsp;</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include TEMPLATE_DIR. "footer.php"; ?>
