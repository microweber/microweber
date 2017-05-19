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

<style>
    .product-info-layout-1 .btn-default {
        font-size: 14px;
        color: #fff;
        text-transform: uppercase;
        background: #000;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        padding: 14px 25px 12px 25px;
        border: 1px solid #000;
    }

    .product-info-layout-1 .btn-default:hover {
        color: #000;
        background: #fff;
    }

    .product-info-layout-1 select,
    .product-info-layout-1 select:hover,
    .product-info-layout-1 select:focus {
        border: 1px solid #000;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        font-weight: bold;
        min-width: 304px;
    }

    .product-info-layout-1 .mw-ui-label {
        font-weight: bold;
        padding-bottom: 5px;
    }

    .product-info-layout-1 .mw-ui-check {
        margin-right: 10px;
    }

    .product-info-layout-1 .mw-ui-check input + span {
        margin: 0;
    }

    .product-info-layout-1 .mw-qty-field {
        margin-top: 10px;
        margin-right: 30px;
    }

    .product-info-layout-1 .mw-qty-field input,
    .product-info-layout-1 .mw-qty-field button {
        float: left;
        display: block;
    }

    .product-info-layout-1 .mw-qty-field button {
        background: #fff;
        border: 1px solid #000;
        color: #000;
        width: 30px;
        height: 30px;
        outline: none;
    }

    .product-info-layout-1 .mw-qty-field button:hover {
        background: #000;
        border: 1px solid #000;
        color: #fff;
    }

    .product-info-layout-1 .mw-qty-field button i {
        font-size: 28px;
    }

    .product-info-layout-1 .mw-qty-field input {
        background: #fff;
        border: 1px solid #fff;
        color: #000;
        height: 30px;
        width: 50px;
        text-align: center;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }

    .product-info-layout-1 .button-add-to-cart,
    .product-info-layout-1 .mw-qty-field {
        display: block;
        float: left;
    }
</style>

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
                                onclick="Alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');"><i
                                    class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                            <?php _e("Out of stock"); ?>
                        </button>
                    <?php else: ?>
                        <button class="btn btn-default pull-right" type="button"
                                onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');"><i
                                    class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
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
</div>
