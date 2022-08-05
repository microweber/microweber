<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <h2 class="edit"  field="title" rel="content"><?php _e('Product inner page'); ?></h2>
        <hr>
        <div class="edit"  field="content" rel="content">
          <div class="mw-row">
            <div class="mw-col" style="width:50%">
              <div class="mw-col-container">
                <module type="pictures"  template="product_gallery" />
              </div>
            </div>
            <div class="mw-col" style="width:50%">
              <div class="mw-col-container">
                <div class="product-description">
                  <div class="edit"  field="content_body" rel="post">
                    <p class="element"><?php _e('This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative & Make Web.'); ?></p>
                  </div>
                  <module type="shop/cart_add" rel="content" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="edit"  field="related_products" rel="inherit">
          <h4 class="element sidebar-title"><?php _e('Related Products'); ?></h4>
          <module type="shop/products" template="4columns" related="true" />
          <p class="element">&nbsp;</p>
        </div>
      </div>
      <!------------ Sidebar -------------->
      <div class="col-sm-3 col-sm-offset-1">
        <?php include_once "shop_sidebar_inner.php"; ?>
      </div>
    </div>
  </div>
</section>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
