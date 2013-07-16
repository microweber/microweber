<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
<?php if(isarr($data )): ?>
<?php $i=1 ;foreach($data  as $key => $v ): ?>

<div class="mw-price-item">
  
  <span class="mw-price pull-left"><?php print  $key ?>: <?php print currency_format($v); ?></span>

  <button class="btn pull-right" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>');"><i class="icon-shopping-cart"></i> <?php _e("Add to cart"); ?></button>
</div>
<?php $i++; endforeach ; ?>
<?php endif; ?>
