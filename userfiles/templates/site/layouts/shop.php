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

<section id="content">
  <div class="container">
    <div class="row">
      <div class="span12 edit"  field="content" rel="page">
        <h2 class="element">Shop page</h2>
        <p class="p0 element">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative & Make Web.</p>
      </div>
    </div>
    <div class="row"> 
      <!-------------- Blog post -------------->
      <div class="span8">
        <module type="shop/products" template="columns" limit="18" description-length="70" hide-paging="n"   />
      </div>
      <!------------ Sidebar -------------->
      <div class="span4">
        <?php include_once ACTIVE_TEMPLATE_DIR. 'layouts' . DS."shop_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
