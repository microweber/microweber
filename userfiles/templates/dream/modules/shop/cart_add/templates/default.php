<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

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
<div class="item__addtocart" style="display: inline-block;">
    <module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price" id="cart_fields_<?php print $params['id'] ?>"/>

    <?php if (is_array($data)): ?>


    <?php foreach ($data as $key => $v): ?>


    <div class="price" id="price_<?php print intval($for_id) . $key; ?>" style="margin-bottom: 30px;">
        <div class="item__price" style="margin-bottom: 20px;">
                    <span>
                        <?php if (is_string($key) and trim(strtolower($key)) == 'price'): ?>
                            <?php _e($key); ?>
                        <?php else: ?>
                            <?php print $key; ?>
                        <?php endif; ?>: <?php print currency_format($v) . $ex_tax; ?>
                    </span>
        </div>


        <?php if (!isset($in_stock) or $in_stock == false) : ?>
            <button class="btn btn--primary" type="button" disabled="disabled"
                    onclick="alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');">
                <?php _e("Out of stock"); ?>
            </button>
        <?php else: ?>
            <button class="btn btn--primary" type="button"
                    onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                <?php _e($button_text !== false ? $button_text : "Add to cart"); ?>
            </button>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>