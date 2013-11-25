<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
 




<?php if(is_array($data )): ?>
<?php $i=1 ;foreach($data  as $key => $v ): ?>

<div class="mw-price-item">
  
  <span class="mw-price pull-left"><?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?><?php _e($key); ?><?php else: ?><?php print $key; ?><?php endif; ?>: <?php print currency_format($v); ?></span>
  
  <?php if(!isset( $in_stock) or  $in_stock == false) : ?>
<button class="btn btn-default pull-right" type="button" disabled="disabled" onclick="Alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered",true)); ?>');"><i class="icon-shopping-cart"></i> <?php _e("Out of stock"); ?></button>
 

<?php else: ?>
  <button class="btn btn-default pull-right" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>');"><i class="icon-shopping-cart"></i> <?php _e("Add to cart"); ?></button>
 
  
  
  
  
  <?php $i++; endif; ?>

 </div>
  <?php if($i > 1) : ?>
   <br />
    <?php endif; ?>

 
<?php $i++; endforeach ; ?>
<?php endif; ?>
 