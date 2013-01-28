<?php

/*

type: layout
content_type: post
name: Shop inner page
is_shop: y
description: shop layout

*/


?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="row"> 
      <!-------------- Product -------------->
      
      <div class="span9">
        <div class="page-header1">
          <h2 class="edit"  field="title" rel="post">Product inner page</h2>
        </div>
        <div class="edit"  field="content" rel="post">
          <p class="p0 element">Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
        </div>
        <div class="clearfix"> <br />
          <br />
        </div>
        <div class="row">
          <div class="span8">
            <div class="span4">
              <module type="pictures" data-content-id="<? print POST_ID ?>" template="slider" />
            </div>
            <div class="span3">
              <module type="custom_fields" data-content-id="<? print POST_ID ?>" class="form-horizontal" id="cart_item"  />
              <span class="button-border button-radius-50 p1"><a class="button-green button-radius-50" href="javascript:mw.cart.add('#cart_item');">Add to cart</a></span> </div>
          </div>
        </div>
      </div>
      
      <!------------ Sidebar -------------->
      <div class="span3">
        <? include_once TEMPLATE_DIR. 'layouts' . DS."shop_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>
<? include TEMPLATE_DIR. "footer.php"; ?>
