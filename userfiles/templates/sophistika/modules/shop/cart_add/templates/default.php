<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
<?php

if(isset($params['content-id'])){
  $product = get_content_by_id($params["content-id"]);
  $title =  $product['title'];
}
else{
  $title =  _e("Product");
}
 
?> 
<script>mw.moduleCSS("<?php print MW_MODULES_URL; ?>shop/cart_add/templates.css")</script>



<script type="text/javascript">

    _AddToCartModalContent = window._AddToCartModalContent || function(title){
        if(title=='' || typeof title == 'undefined'){
          var title = '<?php print _e("Product"); ?>';
        }
	 
		 
          var modal_html = ''
        + '<section> '
          + '<h5>' + title + '</h5>'
          + '<p><?php _e("has been added to your cart"); ?></p>'
          + '<a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="btn btn-default"><?php _e("Continue shopping"); ?></a>'
          + '<a href="<?php print checkout_url(); ?>" class="btn btn-action-default"><?php _e("Checkout"); ?></a>'
        + ' </section>';
 

         return modal_html;   
    }
    _AddToCart = window._AddToCart || function(selector, id, title){
       mw.cart.add(selector, id, function(){
         mw.modal({
            content:_AddToCartModalContent(title),
            template:'mw_modal_basic',
            name:"AddToCartModal",
            width:400,
            height:200,
            overlay:true
         });
       });
    }
   LocalPrice = null;
   SetLocalPrice = window.SetLocalPrice || function(input){
     if(input.checked === true){
         LocalPrice = input.value;
     }
   }

</script>


<?php
  $hasRadios = false;
  if(is_array($data)):
?>
  <div class="mw-product-prices">
<?php
    $hasRadios = count($data) > 1;
    $count = 0;
?>
 <?php if($hasRadios){ ?>

<?php foreach($data  as $key => $v ): ?>

<?php $count++; ?>


<?php if($count == 1){ $firstPrice = $v; ?>

<script>LocalPrice = "<?php print $firstPrice; ?>";</script>

<?php }  ?>

    <div class="mw-price-item">
      <label class="mw-price">
    
        <input type="radio" name="<?php print $params['id'] ?>" value="<?php print $v; ?>" <?php if($count==1){ print 'checked'; } ?> onchange="SetLocalPrice(this);" />
    
      <small>
        <?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
       <?php _e($key); ?>
        <?php else: ?>
        <?php print $key; ?>
        <?php endif; ?>:</small>
        <?php print currency_format($v); ?>
      </label>
    </div>
<?php endforeach ; ?>
 <?php } else{   ?>
<?php  foreach($data  as $key => $v ): ?>
    <div class="mw-price-item">
      <span class="mw-price">
      <small>
        <?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
        <?php _e($key); ?>
        <?php else: ?>
        <?php print $key; ?>
        <?php endif; ?>:</small>
        <?php print currency_format($v); ?>
      </span>
    </div>
<?php endforeach ; ?>
<?php } ?>
</div>
<?php endif; ?>

<module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price"  id="cart_fields_<?php print $params['id'] ?>"  />


  <?php if(!isset( $in_stock) or  $in_stock == false) : ?>
  <button class="btn-action add-to-cart" type="button" disabled="disabled" onclick="Alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered",true)); ?>');">
  <?php _e("Out of stock"); ?>
  </button>
  <?php else: ?>
  <button class="btn-action add-to-cart" type="button" onclick="_AddToCart('.mw-add-to-cart-<?php print $params['id'] ?>', LocalPrice, '<?php print $title; ?>');">
  <?php _e("Add to cart"); ?>
  </button>
  <?php  endif; ?>

