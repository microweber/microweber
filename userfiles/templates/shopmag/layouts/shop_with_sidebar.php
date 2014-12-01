<?php

/*

  type: layout
  content_type: dynamic
  name: Shop with sidebar
  position: 5
  description: Shop with sidebar
  tag: shop
  is_shop: y

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="mw-wrapper content-holder">

<div class="mw-breadcrumb-root"><module type="breadcrumb"></div>


    <div class="mw-ui-row">

         <div class="mw-ui-col" style="width: 200px;">
             <div class="mw-ui-col-container blog-sidebar">
               <div class="item-box pad2">
                   <?php include "sidebar_shop.php"; ?>
               </div>
             </div>
         </div>
         <div class="mw-ui-col">
             <div class="mw-ui-col-container">


                  <div class="XXXedit" rel="page" field="content">

                      <div class="item-box pad2">

                        <module content-id="<?php print PAGE_ID; ?>" type="shop/products" template="shopmag" class="module-products-template-shopmag-3columns" />

                      </div>
                  </div>
              </div>
         </div>
    </div>
</div>

<?php include TEMPLATE_DIR. "footer.php"; ?>

