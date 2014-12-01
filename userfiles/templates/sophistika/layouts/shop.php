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
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="container">
    <div class="box-container">
		<div class="row" id="shop-products-conteiner">
			<div class="col-md-9">
                <h2 class="edit page-title" field="title" rel="content">Shop</h2>
                  <div class="edit" field="content" rel="page">
                       
                  </div>
    			<div class="edit" field="shop-content" rel="page">
    				<div class="box-container">
                        <module type="shop/products" template="2columns" limit="18" description-length="70" data-show="thumbnail,title,add_to_cart,price" />
                    </div>
    			</div>
			</div>
			<div class="col-md-3">
				<?php
              		include TEMPLATE_DIR. 'layouts' . DS."shop_sidebar.php"
		        ?>
			</div>
		</div>
	</div>
</div>

<?php include TEMPLATE_DIR. "footer.php"; ?>
