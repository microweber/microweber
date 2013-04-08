<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: shop layout

*/


?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="row">
      <div class="span12 edit"  field="content" rel="page">
        <h2 class="element">Shop page</h2>
        <p class="p0 element">Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
      </div>
    </div>
    <div class="row"> 
      <!-------------- Blog post -------------->
      <div class="span8">
        <module type="shop/products" template="columns"   />
      </div>
      <!------------ Sidebar -------------->
      <div class="span4">
        <? include_once ACTIVE_TEMPLATE_DIR. 'layouts' . DS."shop_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
