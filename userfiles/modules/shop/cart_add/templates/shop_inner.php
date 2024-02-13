<?php

/*

type: layout

name: Shop Inner

description: Shop Inner

*/
?>
<?php

if (isset($params['content-id'])) {
    $product = get_content_by_id($params["content-id"]);
    $title = $product['title'];
} else {
    $title = _e("Product", true);
}


?>

<br class="mw-add-to-cart-spacer"/>

<module type="custom_fields" template="bootstrap5_flex" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price" id="cart_fields_<?php print $params['id'] ?>"/>
<?php if (is_array($data)): ?>
    <div class="price">
        <?php $i = 1;

        foreach ($data as $key => $v): ?>
            <div class="mw-price-item d-flex align-items-center justify-content-between ">


                <?php

                $keyslug_class = str_slug(strtolower($key));


                // $key = $price_offers[$key]['offer_price'];

                ?>


                <div class="price-holder">

                    <h5 class="mb-0 price"><?php print currency_format($v); ?></h5>
                </div>


                <?php if (!isset($in_stock) or $in_stock == false) : ?>
                    <button class="btn btn-default pull-right" type="button" disabled="disabled"
                            onclick="mw.alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');">
                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                        <?php _e("Out of stock"); ?>
                    </button>
                <?php else: ?>


                    <button class="btn btn-primary pull-right" type="button"
                            onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                        <?php _e($button_text !== false ? $button_text : "Add to cart"); ?>
                    </button>


                    <?php $i++; endif; ?>


            </div>
            <?php if ($i > 1) : ?>
                <br/>
            <?php endif; ?>
            <?php $i++; endforeach; ?>
    </div>
<?php endif; ?>
