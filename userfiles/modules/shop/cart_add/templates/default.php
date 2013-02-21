<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
<? if(isarr($data )): ?>
<? $i=1 ;foreach($data  as $k  => $v): ?>




<div class="mw-price-item"> <span class="mw-price"><? print $k ?>: <? print currency_format($v); ?></span>

  <button class="btn btn-primary" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $params['id'] ?>','<? print $v ?>');">Add to cart</button>
</div>
<? $i++; endforeach ; ?>
<? endif; ?>
