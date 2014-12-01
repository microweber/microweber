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

<div class="mw-wrapper content-holder">
  <div class="eXdit" rel="page" field="content">
        <div class="mw-row" style="width:100%;">
            <div class="mw-col" style="width: 50%;">
              <div class="mw-col-container">
                  <div class="mw-breadcrumb-root"><module type="breadcrumb"></div>
              </div>
            </div>
            <div class="mw-col" style="width: 25%;">

            </div>
            <div class="mw-col" style="width: 25%;">
                <div class="mw-col-container">
                    <module type="categories" template="dropdown" class="pull-right w100" />
                </div>
            </div>
        </div>
        <div class="item-box pad2"><module type="shop/products" template="shopmag" limit="18" description-length="70" data-show="thumbnail,title,add_to_cart,price" /></div>
  </div>
</div>



<?php include TEMPLATE_DIR. "footer.php"; ?>
