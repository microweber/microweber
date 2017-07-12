<?php
$next = next_content();

$prev = prev_content();
?>

<script>mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');</script>
<script>mw.moduleCSS("<?php print modules_url(); ?>layouts/blocks/styles.css"); </script>

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


    <h1 class="edit" field="title" rel="post">Product inner page</h1>
    <div class="price"><span><?php print currency_format(get_product_price()); ?></span></div>

    <module type="rating" content-id="<?php print CONTENT_ID; ?>"/>
    <div class="edit" field="content_body" rel="post">
        <p class="description">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it
            anywhere on the site. Get creative &amp; <strong>Make Web</strong>.</p>
    </div>
</div>

<module type="shop/cart_add" template="shop-cart-1"/>

<div class="product-info-layout-1">
    <module type="sharer" id="product-sharer">
</div>