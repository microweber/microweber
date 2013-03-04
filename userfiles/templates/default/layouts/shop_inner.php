<?php

/*

type: layout
content_type: product
name: Product inner page
description: shop layout

*/



?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <h2 class="edit"  field="title" rel="post">Product inner page</h2>
    <hr>
    <div class="row">
    <div class="span9"> <!-------------- Product -------------->
        <div class="row">
              <div class="span5">
                <module type="pictures" data-content-id="<? print POST_ID ?>" template="slider_shop" />
              </div>
              <div class="span4 product-description">
               <div class="edit"  field="content" rel="post">
                <p class="p0 element">Describe your product</p>
               </div>

                <module type="shop/cart_add" data-content-id="<? print POST_ID; ?>" />
              </div>
        </div>
      </div>
      <!------------ Sidebar -------------->
      <div class="span3">
        <? include_once ACTIVE_TEMPLATE_DIR. 'layouts' . DS."shop_sidebar.php"; ?>
      </div>
  </div>
  </div>
</section>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
