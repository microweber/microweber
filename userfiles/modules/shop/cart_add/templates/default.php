<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
<? if(isarr($data )): ?>
<? $i=1 ;foreach($data  as $key => $v ): ?>

<div class="mw-price-item">
  
  <span class="mw-price"><? print  $key ?>: <? print currency_format($v); ?></span>
  <button class="btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $params['id'] ?>','<? print $v ?>');"><i class="icon-shopping-cart"></i> Add to cart</button>
</div>
<? $i++; endforeach ; ?>
<? endif; ?>
