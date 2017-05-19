<?php
$next = next_content();

$prev = prev_content();
?>

<style>
    .product-info-layout-1 h1 {
        font-size: 24px;
        color: #373737;
    }

    .product-info-layout-1 .price {
        font-size: 24px;
        color: #989898;
    }

    .product-info-layout-1 .description {
        font-size: 18px;
        color: #373737;
    }

    .product-info-layout-1 .next-previous-content {
        text-align: right;
    }

    .product-info-layout-1 .next-previous-content a,
    .product-info-layout-1 .next-previous-content i {
        width: 25px;
        color: #000;
    }

    .product-info-layout-1 .module-sharer{
        margin-top:30px;
    }

    .product-info-layout-1 .module-sharer .mw-social-share-links a {
        border-radius: 25px;
        padding-top: 8px;
    }

    .product-info-layout-1 .module-sharer .mw-social-share-links a:hover {
        color: #f1efee;
    }
</style>

<div class="product-info-layout-1">
    <div class="next-previous-content">
        <?php if ($prev != false) { ?>
            <a href="<?php print content_link($prev['id']); ?>" class="prev-content tip" data-tip="#prev-tip"><i class="material-icons">arrow_backward</i></a>
            <div id="prev-tip" style="display: none">
                <div class="next-previous-tip-content">
                    <img src="<?php print get_picture($prev['id']); ?>" alt="" width="90"/>
                    <h6><?php print $prev['title']; ?></h6>
                </div>
            </div>
        <?php } ?>

        <a href="<?php print site_url(); ?>shop"><i class="material-icons">reorder</i></a>

        <?php if ($next != false) { ?>
            <a href="<?php print $next['url']; ?>" class="next-content tip" data-tip="#next-tip"><i class="material-icons">arrow_forward</i></a>
            <div id="next-tip" style="display: none">
                <div class="next-previous-tip-content">
                    <img src="<?php print get_picture($next['id']); ?>" alt="" width="90"/>
                    <h6><?php print $next['title']; ?></h6>
                </div>
            </div>
        <?php } ?>
    </div>


    <div class="edit" field="content" rel="post">
        <h1 class="edit" field="title" rel="post">Product inner page</h1>
        <div class="price"><span>$80.09</span></div>

        <module type="rating"/>

        <div class="edit" field="content_body" rel="post">
            <p class="description">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it
                anywhere on the site. Get creative &amp; <strong>Make Web</strong>.</p>
        </div>
    </div>
</div>

<module type="shop/cart_add" template="shop-cart-1"/>

<div class="product-info-layout-1">
    <module type="sharer" id="product-sharer">
</div>