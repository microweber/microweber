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
  $title =  _e("Product", true);
}
 
?> 
<script>mw.moduleCSS("<?php print modules_url(); ?>shop/cart_add/templates.css")</script>



<script type="text/javascript">
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
  <button class="btn-action add-to-cart" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>', LocalPrice, '<?php print $title; ?>');">
  <?php _e("Add to cart"); ?>
  </button>
  <?php  endif; ?>

