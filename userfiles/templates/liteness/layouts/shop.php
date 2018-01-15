<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: Shop Layout
position: 3
*/


?>
<?php include TEMPLATE_DIR . "header.php"; ?>

<div class="edit" rel="content" field="liteness_content">
    <div class="container nodrop">
        <div class="box-container">
            <div class="row" id="shop-products-conteiner">
                <div class="col-md-9">
                    <h2 class="page-title">Shop</h2>
                    <div class="box-container">
                        <module type="shop/products" template="2columns" description-length="70" data-show="thumbnail,title,add_to_cart,price"/>
                    </div>
                </div>

                <div class="col-md-3">
                    <?php include TEMPLATE_DIR . 'layouts' . DS . "shop_sidebar.php" ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . "footer.php"; ?>
