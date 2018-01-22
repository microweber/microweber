<?php if ($shop2_header_style == '' OR $shop2_header_style == 'background'): ?>
    <section class="imagebg image--light height-60" data-overlay="1">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>header4.jpg');"></div>

        <div class="container pos-vertical-center">
            <div class="row">
                <div class="col-sm-4 shop-item-detail">
                    <h3><?php print content_title(); ?></h3>
                    <div class="edit" field="content_body_short" rel="content">
                        <p>
                            <?php _lang("This timeless staple represents a casual, elegant addition to any summer wardrobe ", "templates/dream"); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="shop-item-detail shop-item-detail-1">
                <div class="col-sm-6">
                    <module type="pictures" rel="content" template="skin-3"/>
                </div>
                <div class="col-md-4 col-md-offset-1 col-sm-6">
                    <div class="item__title">
                        <h4 class="edit" field="title" rel="content"><?php _lang("Product name", "templates/dream"); ?></h4>
                    </div>

                    <div class="item__price" style="margin-bottom: 10px;">
                        <span><?php print currency_format(get_product_price()); ?></span>
                    </div>

                    <div class="clearfix" style="margin-bottom: 30px;">
                        <?php $content_data = content_data(CONTENT_ID);
                        $in_stock = true;
                        if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {
                            $in_stock = false;
                        }
                        ?>
                        <?php if ($in_stock == true): ?>
                            <span class="text-success"><i class="fa fa-check"></i> <?php _e("In Stock") ?></span>
                        <?php else: ?>
                            <span class="text-danger"><i class="glyphicon glyphicon-remove"></i> <?php _e("Out of Stock") ?></span>
                        <?php endif; ?>
                        <div class="clearfix"></div>
                        <?php if (isset($content_data['sku'])): ?>
                            <strong><?php _e("SKU") ?>:</strong> <?php print $content_data['sku']; ?>
                        <?php endif; ?>
                    </div>

                    <module type="shop/cart_add"/>

                    <div class="item__description">
                        <h6><?php _e('Description'); ?>:</h6>

                        <div class="edit" field="content_body" rel="content">
                            <p>
                                <?php _lang("A sturdy, handwoven fabric makes this American Apparel indigo-T a dependable addition to your casual wardrobe. This is a no bullshit plain ol’ t-shirt.", "templates/dream"); ?>
                            </p>
                        </div>
                    </div>
                    <div class="item__description">
                        <h6><?php _e('Information'); ?>:</h6>

                        <div class="edit safe-mode" field="product_sheets" rel="content">
                            <div class="item__subinfo cloneable">
                                <span class="safe-element"><?php _lang("Fabric", "templates/dream"); ?></span>
                                <span class="safe-element">100% Cotton</span>
                            </div>
                            <div class="item__subinfo cloneable">
                                <span class="safe-element"><?php _lang("Origin", "templates/dream"); ?></span>
                                <span class="safe-element"><?php _lang("Handmade in Aus", "templates/dream"); ?></span>
                            </div>
                            <div class="item__subinfo cloneable">
                                <span class="safe-element"><?php _lang("Weight", "templates/dream"); ?></span>
                                <span class="safe-element">280gm - 340gm</span>
                            </div>
                            <div class="item__subinfo cloneable">
                                <span class="safe-element"><?php _lang("Sizes", "templates/dream"); ?></span>
                                <span class="safe-element">S,M,L,XL</span>
                            </div>
                        </div>
                    </div>

                    <div class="item__description">
                        <hr>
                        <module type="sharer" template="skin-1" id="product-sharer"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="related-products">
                <div class="col-sm-12">
                    <h4><?php _e('Related Products'); ?></h4>
                </div>
                <module type="shop/products" template="skin-4" related="true" limit="3" />
            </div>
        </div>
    </div>
</section>