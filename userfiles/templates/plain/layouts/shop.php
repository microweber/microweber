<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: shop layout

*/


?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

    <div class="container">
        <module type="shop/products" data-limit="999"   data-description-length="50"   data-show="thumbnail,title,add_to_cart,description,price"   data-template="mwcolumns" />

         </div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
