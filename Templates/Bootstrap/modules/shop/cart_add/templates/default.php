<?php

/*

type: layout

name: Default

description: Default

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

<div class="row product-custom-fields-holder">
    <div class="col-md-4 col">&nbsp;</div>
    <div class="col-md-8 col">
        <module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price" id="cart_fields_<?php print $params['id'] ?>"/>
    </div>
</div>

<?php if (is_array($data)): ?>
    <div class="price">
        <?php $i = 1;

        foreach ($data as $key => $v): ?>
            <div class="mw-price-item font-weight-bold">

                <?php $keyslug_class = str_slug(strtolower($key)); ?>

                <?php if (is_string($key) and trim(strtolower($key)) == 'price'): ?>
                    <span class="mw-price-item-key mw-price-item-key-<?php print ($keyslug_class); ?>">
                    <?php _lang($key, "templates/big"); ?>
                </span>

                <?php else: ?>
                    <span class="mw-price-item-key mw-price-item-key-<?php print ($keyslug_class); ?>">
                    <?php print $key; ?>
                </span>
                <?php endif; ?>:

                <span class="mw-price-item-value"><?php print currency_format($v); ?></span>

                <?php if (!isset($in_stock) or $in_stock == false) : ?>
                    <button class="btn btn-secondary float-srart" type="button"
                            onclick="alert('<?php _e("This item is out of stock and cannot be ordered"); ?>');">
                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                        <?php _lang("Out of stock", "templates/big"); ?>
                    </button>
                <?php else: ?>

                    <button class="btn btn-primary btn-sm float-end" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                        <?php _lang($button_text !== false ? $button_text : "Add to cart", "templates/big"); ?>
                    </button>
                    <div class="clearfix"></div>
                    <?php $i++; endif; ?>
            </div>
            <?php if ($i > 1) : ?>
                <br/>
            <?php endif; ?>
            <?php $i++; endforeach; ?>
    </div>
<?php endif; ?>
