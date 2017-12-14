<?php
/*
type: layout
name: Checkout
position: 3
description: Checkout
*/
?>

<?php include template_dir() . "header.php"; ?>

<section class="height-40 page-title">
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2 class="edit" rel="content" field="title">Shopping Cart</h2>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="cart-form form--square">
            <div field="checkout_page" rel="content">
                <module type="shop/checkout" id="cart_checkout"/>
            </div>
        </div>
    </div>
</section>

<?php include template_dir() . "footer.php"; ?>


