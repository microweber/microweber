<?
$orders = get_orders();
 
?>
<? if(isarr($orders)) :?>
<? foreach ($orders as $item) : ?>

<div class="mw-order-item mw-order-item-<? print $item['id'] ?>">
  <div class="mw-order-item-id"> ORD: <? print $item['id'] ?></div>
  <div class="mw-order-item-amount"> amount: <? print $item['amount'] ?></div>
  <div class="mw-order-item-count"> items count: <? print $item['items_count'] ?></div>
  <div class="mw-order-item-count"> items count: <? print $item['items_count'] ?></div>
  <div class="mw-order-item-first-name"> first_name: <? print $item['first_name'] ?></div>
  <div class="mw-order-item-last-name"> last_name: <? print $item['last_name'] ?></div>
  <div class="mw-order-item-country"> country: <? print $item['country'] ?></div>
  <div class="mw-order-item-email"> email: <? print $item['email'] ?></div>
  <div class="mw-order-item-phone"> phone: <? print $item['phone'] ?></div>
  <div class="mw-order-item-edit"><a href="javascript:mw.url.windowHashParam('vieworder','<? print ($item['id']) ?>');">View order</a></div>
  <hr>
</div>
<? endforeach; ?>
<? else: ?>
<h2>You don't have any orders</h2>
<? endif;?>
