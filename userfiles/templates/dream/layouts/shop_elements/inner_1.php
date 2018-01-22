<?php if ($shop1_header_style == '' OR $shop1_header_style == 'clean'): ?>
    <section class="imagebg image--light height-60" data-overlay="1">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>header4.jpg');"></div>

        <div class="container pos-vertical-center">
            <div class="row">
                <div class="col-sm-4 shop-item-detail">
                    <h3><?php print content_title(); ?></h3>
                    <div class="edit" field="content_body_short" rel="content">
                        <p>
                            <?php _lang("This timeless staple represents a casual, elegant addition to any summer wardrobe", "templates/dream"); ?>
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
            <div class="shop-item-detail shop-item-detail-2">
                <div class="col-sm-12">
                    <module type="pictures" rel="content" template="skin-2"/>
                </div>

                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                    <div class="item__title">
                        <h4 class="edit" field="title" rel="content"><?php _e("Product name") ?></h4>
                    </div>

                    <div class="clearfix" style="margin-bottom: 30px;">
                        <?php $content_data = content_data(CONTENT_ID);
                        $in_stock = true;
                        if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {
                            $in_stock = false;
                        }
                        ?>

                        <?php if (isset($content_data['sku'])): ?>
                            <strong><?php _e("SKU") ?>:</strong> <?php print $content_data['sku']; ?>
                        <?php endif; ?>
                        <br/>
                        <br/>
                        <?php if ($in_stock == true): ?>
                            <span class="text-success"><i class="fa fa-check"></i> <?php _e("In Stock") ?></span>
                        <?php else: ?>
                            <span class="text-danger"><i class="glyphicon glyphicon-remove"></i> <?php _e("Out of Stock") ?></span>
                        <?php endif; ?>
                    </div>

                    <module type="shop/cart_add"/>
                </div>

                <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">
                    <div class="tabs-container tabs-2">
                        <ul class="tabs text-center">
                            <li class="active">
                                <div class="tab__title">
                                    <h6><?php _e('Description'); ?></h6>
                                </div>
                                <div class="tab__content">
                                    <div class="edit" field="content_body" rel="content">
                                        <p>
                                            <?php _lang("Strategy gamification alpha startup angel investor channels customer direct mailing burn rate churn rate bandwidth innovator seed round. Ramen
                                            disruptive
                                            graphical user interface. Infrastructure bootstrapping branding leverage twitter channels MVP iPad launch party non-disclosure agreement. Infrastructure
                                            validation android release success.", "templates/dream"); ?>
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="tab__title">
                                    <h6><?php _e('Specifications'); ?></h6>
                                </div>
                                <div class="tab__content">
                                    <div class="item__description">
                                        <div class="edit" field="product_sheets" rel="content">
                                            <div class="table-responsive">
                                                <table class="table table-hover item__subinfo">
                                                    <thead>
                                                    <tr>
                                                        <th><?php _lang("Column name", "templates/dream"); ?></th>
                                                        <th><?php _lang("Column name", "templates/dream"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><?php _lang("Size", "templates/dream"); ?></td>
                                                        <td>2XL</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _lang("Color", "templates/dream"); ?></td>
                                                        <td>Red</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _lang("Weight", "templates/dream"); ?></td>
                                                        <td>132lbs</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _lang("Height", "templates/dream"); ?></td>
                                                        <td>74cm</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _lang("Bluetooth", "templates/dream"); ?></td>
                                                        <td><i class="fa fa-check text-success"></i> <?php _lang("YES", "templates/dream"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _lang("Wi-Fi", "templates/dream"); ?></td>
                                                        <td><i class="glyphicon glyphicon-remove text-danger"></i> <?php _lang("NO", "templates/dream"); ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="tab__title">
                                    <h6><?php _e('Reviews'); ?></h6>
                                </div>
                                <div class="tab__content">
                                    <module type="comments" content-id="<?php print CONTENT_ID; ?>"/>

                                    <ul class="item__reviews">
                                        <li>
                                            <img alt="pic" src="img/avatar-small-1.png"/>
                                            <div class="review__text">
                                                <div class="review__score">
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star empty"></div>
                                                </div>
                                                <h5><?php _lang("Sarah King", "templates/dream"); ?></h5>
                                                <span class="type--fine-print"><?php _lang("February 4th, 2018", "templates/dream"); ?></span>
                                                <p>
                                                    <?php _lang("This wallet is the highest quality I've purchased. The leather is luxuioursly delish.", "templates/dream"); ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <img alt="pic" src="img/avatar-small-2.png"/>
                                            <div class="review__text">
                                                <div class="review__score">
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                </div>
                                                <h5><?php _lang("Luke Saunders", "templates/dream"); ?></h5>
                                                <span class="type--fine-print"><?php _lang("January 28th, 2016", "templates/dream"); ?></span>
                                                <p>
                                                    <?php _lang("What a beautifully crafted wallet. It holds all my cards comfortably and even after six months it's showing no signs of ware - none of that
                                                    annoying 'card imprint' that I've had with inferior brands.", "templates/dream"); ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <img alt="pic" src="img/avatar-small-4.png"/>
                                            <div class="review__text">
                                                <div class="review__score">
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star"></div>
                                                    <div class="star empty"></div>
                                                    <div class="star empty"></div>
                                                </div>
                                                <h5><?php _lang("Bonnie Thompson", "templates/dream"); ?></h5>
                                                <span class="type--fine-print"><?php _lang("January 13th, 2016", "templates/dream"); ?></span>
                                                <p>
                                                    <?php _lang("I loved the wallet but found the colour to be a little less brown than it appeared in the photos.", "templates/dream"); ?>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg--white">
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
