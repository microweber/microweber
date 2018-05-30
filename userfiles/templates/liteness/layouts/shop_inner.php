<?php include TEMPLATE_DIR . "header.php"; ?>
<div class="edit" rel="content" field="liteness_content">
    <section id="content">
        <div class="container">
            <div class="box-container">
                <div>
                    <div class="mw-ui-row shop-product-row">
                        <div class="mw-ui-col" style="width:65%">
                            <div class="mw-ui-col-container">
                                <module type="pictures" rel="content" template="product_gallery_multiline"/>
                            </div>
                        </div>
                        <div class="mw-ui-col" style="width:35%">
                            <div class="mw-ui-col-container">
                                <h2 class="edit" field="title" rel="content">Product inner page</h2>
                                <div class="product-description">
                                    <div class="edit" field="content_body" rel="post">

                                        <p class="element">This text is set by default and it is suitable for edit in real time. By default the drag and drop core feature will allow you to position it
                                            anywhere on the site. Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.</p>
                                    </div>
                                    <module type="shop/cart_add" rel="post"/>
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
                    <div class="latest-items">
                        <h2 class="section-title element"><?php _e('Related Products'); ?></h2>
                        <module type="shop/products" related="true" limit="3" ajax-paging="true" data-show="thumbnail,title,add_to_cart,price"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include TEMPLATE_DIR . "footer.php"; ?>
