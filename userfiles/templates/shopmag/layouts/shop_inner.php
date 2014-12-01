<?php include TEMPLATE_DIR . "header.php"; ?>
 <?php
        $next = next_content();
        $prev = prev_content();
    ?>
<div class="mw-wrapper" style="padding: 40px 0px;">
    <div class="edit" field="content" rel="post">
        <div class="mw-ui-row shop-product-row">
            <div class="mw-ui-col" style="width:45%">
                <div class="mw-ui-col-container">
                    <div class="item-box" id="product-page-gallery">
                        <module type="pictures" rel="content" template="product_gallery"/>
                    </div>
                </div>
            </div>
            <div class="mw-ui-col" style="width:55%">
                <div class="mw-ui-col-container">
                    <div class="product-description">
                        <div class="item-box pad">
<div class="mw-ui-row">
    <div class="mw-ui-col"><module type="breadcrumb"></div>
    <div class="mw-ui-col">

   
    <div class="next-previous-content">

    <?php if($prev != false){  ?>
        <a href="<?php print $prev['url']; ?>" class="mw-icon-arrow-left-slim prev-content tip" data-tip="#prev-tip"></a>
        <div id="prev-tip" style="display: none">
            <div class="next-previous-tip-content">
                <img src="<?php print get_picture($prev['id']); ?>" alt="" width="90" />
                <h6><?php print $prev['title']; ?></h6>
            </div>
        </div>
    <?php } ?>
        <?php if($next != false){  ?>
        <a href="<?php print $next['url']; ?>" class="mw-icon-arrow-right-slim next-content tip" data-tip="#next-tip"></a>
        <div id="next-tip" style="display: none">
            <div class="next-previous-tip-content">
                <img src="<?php print get_picture($next['id']); ?>" alt="" width="90" />
                <h6><?php print $next['title']; ?></h6>
            </div>
        </div>
    <?php } ?>
    </div>
    </div>
</div>
                          <h2 class="edit post-title" field="title" rel="post">Product inner page</h2>

                          <div class="edit product-description" field="content_body" rel="post">
                              <p class="element">This text is set by default and it is suitable for edit in real time. By
                                  default the drag and drop core feature will allow you to position it anywhere on the
                                  site. Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.
                              </p>
                          </div>
                          <div class="cart-add-box">
                              <module type="shop/cart_add" rel="post"/>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="item-box" id="product-related">

        <h2 class="section-title element">Related Products</h2>
        <hr>
        <module type="shop/products" template="horizontal_slider" related="true" limit="7" ajax-paging="true"
                data-show="thumbnail,title,add_to_cart,price"/>

    </div>

    <?php /*<div class="latest-items">
            <h2 class="section-title element">Related Products</h2>
                <module type="shop/products" related="true" limit="3" ajax-paging="true" data-show="thumbnail,title,add_to_cart,price" />
            <p class="element">&nbsp;</p>
          </div>*/ ?>
</div>
<?php include TEMPLATE_DIR . "footer.php"; ?>
