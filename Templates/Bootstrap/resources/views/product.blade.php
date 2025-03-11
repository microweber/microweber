<?php
$content_data = content_data(content_id());
$in_stock = true;
if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {
    $in_stock = false;
}

if (isset($content_data['qty']) and $content_data['qty'] == 'nolimit') {
    $available_qty = '';
} elseif (isset($content_data['qty']) and $content_data['qty'] != 0) {
    $available_qty = $content_data['qty'];
} else {
    $available_qty = 0;
}

$item = get_content_by_id(content_id());
$itemData = content_data(content_id());
$itemTags = content_tags(content_id());

if (!isset($itemData['label'])) {
    $itemData['label'] = '';
}
if (!isset($itemData['label-color'])) {
    $itemData['label-color'] = '';
}

$next = next_content(content_id());
$prev = prev_content(content_id());


?>
@extends('templates.bootstrap::layouts.master')

@section('content')

    <div class="shop-inner-page " id="shop-content-<?php print CONTENT_ID; ?>" field="shop-inner-page" rel="page">
        <section class="py-md-5 mb-md-5 fx-particles">
            <div class="container-fluid mw-layout-container mw-m-t-30 mw-m-b-50">
                <div class="row justify-content-center">
                    <div class="row product-holder">
                        <div class="col-12 col-md-6 col-lg-6">
                            <module type="pictures" rel="content" template="shop-inner-templates"/>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 relative product-info-wrapper">
                            <div class="product-info">
                                <div class="product-info-content">
                                    <div class="heading mt-sm-4 mt-md-0 pb-0 mb-2">
                                        <h1 class="edit d-inline-block" field="title"
                                            rel="content"><?php print content_title(); ?></h1>

                                        <div class="next-previous-content float-end">
                                            <?php if ($prev != false) { ?>
                                            <a href="<?php print content_link($prev['id']); ?>"
                                               class="prev-content tip btn btn-outline-default" data-tip="#prev-tip"><i
                                                    class="mdi mdi-arrow-left"></i></a>
                                            <div id="prev-tip" style="display: none">
                                                <div class="next-previous-tip-content text-center">
                                                    <img src="<?php print get_picture($prev['id']); ?>" alt=""
                                                         width="90"/>
                                                    <h6><?php print $prev['title']; ?></h6>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <?php if ($next != false) { ?>
                                            <a href="<?php print $next['url']; ?>"
                                               class="next-content tip btn btn-outline-default" data-tip="#next-tip"><i
                                                    class="mdi mdi-arrow-right"></i></a>

                                            <div id="next-tip" style="display: none">
                                                <div class="next-previous-tip-content text-center">
                                                    <img src="<?php print get_picture($next['id']); ?>" alt=""
                                                         width="90"/>

                                                    <h6><?php print $next['title']; ?></h6>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row main-price">
                                        <div class="col-12 d-flex">
                                            <div class="col-6">
                                                <?php $prices = get_product_prices(content_id(), true); ?>
                                                <?php if (isset($prices[0]) and is_array($prices)) { ?>
                                                <p>
                                                        <?php if (isset($prices[0]['original_value'])): ?><span
                                                        class="price-old"><?php print currency_format($prices[0]['original_value']); ?></span><?php endif; ?>
                                                                                                                                                  <?php if (isset($prices[0]['value'])): ?>
                                                    <span
                                                        class="price"><?php print currency_format($prices[0]['value']); ?></span><?php endif; ?>
                                                </p>
                                                <?php } ?>
                                            </div>

                                            <div class="availability col-6 text-end text-right align-self-center">
                                                <?php if ($in_stock == true): ?>
                                                <span class="text-success"><i class="fa fa-circle"
                                                                              style="font-size: 8px;"></i> <?php _lang("In Stock", 'templates/big') ?></span>
                                                <span
                                                    class="text-muted"><?php if ($available_qty != ''): ?>(<?php echo $available_qty; ?>)<?php endif; ?></span>
                                                <?php else: ?>
                                                <span class="text-danger"><i class="fa fa-circle"
                                                                             style="font-size: 8px;"></i> <?php _lang("Out of Stock", 'templates/big') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <?php if (isset($content_data['sku'])): ?>
                                                <?php _lang("SKU", 'templates/big') ?>
                                            - <?php print $content_data['sku']; ?>
                                              <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="description">
                                                <div class="edit" field="content_body" rel="content">
                                                    <p><?php _lang("How to write product descriptions that sell", 'templates/big') ?></p>
                                                    <p><?php _lang("One of the best things you can do to make your store successful is invest some time in writing great product descriptions. You want to provide detailed yet concise information that will entice potential customers to buy.", 'templates/big') ?></p>

                                                    <p><?php _lang("Think like a consumer", 'templates/big') ?></p>
                                                    <p><?php _lang("Think about what you as a consumer would want to know, then include those features in your description. For clothes: materials and fit. For food: ingredients and how it was prepared. Bullets are your friends when listing
                                                        features — try to
                                                        limit each one to 5-8 words.", 'templates/big') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bold">
                                        <module type="shop/cart_add"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="edit safe-mode nodrop py-5" field="related_products" rel="content">
                            <div class="col-12 text-start text-left mb-4">
                                <h2 class="related-title"><?php _lang('Related products', 'templates/shopmag'); ?></h2>
                            </div>

                            <div class="col-12">
                                <module type="shop/products" template="skin-1" related="true" limit="4"
                                        hide_paging="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
