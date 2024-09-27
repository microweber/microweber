<?php
/*
type: layout

name: Shop cart 1

description: Add to cart

*/

?>

<script>
    $(document).ready(function () {
        $(".mw-qty-field .cartDecreaseProductsNumber").click(function () {
            var thisQty = $(this).parent().find('input');
            var inputVal = thisQty.val();

            if (inputVal < 2) {
                inputVal = 1;
                thisQty.val(inputVal);
            } else {
                inputVal = inputVal - 1;
                thisQty.val(inputVal);
            }
        });

        $(".mw-qty-field .cartIncreaseProductsNumber").click(function () {
            var thisQty = $(this).parent().find('input');
            var inputVal = thisQty.val();
            inputVal = parseInt(inputVal) + (1);
            thisQty.val(inputVal);
        });
    });
</script>
<div class="product-info-layout-1">
    <?php
    if (isset($params['content-id'])) {
        $product = get_content_by_id($params["content-id"]);
        $title = $product['title'];
    } else {
        $title = _e("Product", true);
    }
    ?>


    <br class="mw-add-to-cart-spacer"/>
    <module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price" id="cart_fields_<?php print $params['id'] ?>"/>

    <div class="mw-qty-field">
        <button class="cartDecreaseProductsNumber" type="button"><i class="material-icons">expand_less</i></button>
        <input type="text" name="qty" value="1"/>
        <button class="cartIncreaseProductsNumber" type="button"><i class="material-icons">expand_more</i></button>

    </div>

    <?php if (is_array($data)): ?>
        <div class="button-add-to-cart">
            <?php $i = 1; ?>
            <?php foreach ($data as $key => $v): ?>
                <div class="mw-price-item">
                    <?php if (!isset($in_stock) or $in_stock == false) : ?>
                        <button class="btn btn-default pull-right" type="button" disabled="disabled"
                                onclick="alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');"><i
                                    class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                            <?php _lang("Out of stock", "templates/big"); ?>
                        </button>
                    <?php else: ?>
                        <button class="btn btn-default pull-right" type="button"
                                onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');"><i
                                    class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                            <?php _lang($button_text, "templates/big" !== false ? $button_text : "Add to cart", "templates/big"); ?>
                        </button>
                        <?php $i++; endif; ?>
                </div>
                <?php if ($i > 1) : ?>
                    <br/>
                <?php endif; ?>
                <?php $i++; endforeach; ?>
        </div>
    <?php endif; ?>
</div>
