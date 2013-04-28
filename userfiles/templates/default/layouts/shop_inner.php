<?php

/*

type: layout
content_type: product
name: Product inner 
description: shop layout

*/



?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="row">
      <div class="span9"> <!-------------- Product -------------->
        
        <h2 class="edit"  field="title" rel="post">Product inner page</h2>
        <hr>
        <div class="edit"  field="content" rel="post">
          <div class="row">
            <div class="span5">
              <module type="pictures" rel="post" template="product_gallery" id="test1" />
            </div>
            <div class="span4 product-description">
              <p class="p0 element">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative &amp; <strong style="font-weight: 600">Make Web</strong>.</p>
              <module type="shop/cart_add" rel="post" />
            </div>
            <p class="element">&nbsp;</p>
            
            <p class="element">&nbsp;</p>
            <p class="element">&nbsp;</p>
          </div>
        </div>
        
        <!-------------- Related Products -------------->
        
        <h4 class="element sidebar-title">Related Products</h4>
        <module type="shop/products" template="4columns" related="true" />
        <p class="element">&nbsp;</p>
      </div>
      <!------------ Sidebar -------------->
      <div class="span3">
        <? include_once ACTIVE_TEMPLATE_DIR. 'layouts' . DS."shop_sidebar_inner.php"; ?>
      </div>
    </div>
  </div>
</section>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
