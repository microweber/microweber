<?



$ord = get_orders($params['order-id']);
$cart_items = array();
if(isarr($ord[0])){
	$ord = $ord[0];
	$cart_items = get_cart('no_session_id=1');
	
	
} else {
	
error("Invalid order id");	
}

 
?>

<h2>Client information</h2>
<div class="mw-order-ord mw-order-ord-<? print $ord['id'] ?>">
  <div class="mw-order-ord-first-name"> first_name: <? print $ord['first_name'] ?></div>
  <div class="mw-order-ord-last-name"> last_name: <? print $ord['last_name'] ?></div>
  <div class="mw-order-ord-country"> country: <? print $ord['country'] ?></div>
  <div class="mw-order-ord-email"> email: <? print $ord['email'] ?></div>
  <div class="mw-order-ord-phone"> phone: <? print $ord['phone'] ?></div>
  <div class="mw-order-ord-ip"> user_ip: <? print $ord['user_ip'] ?></div>
  <div class="mw-order-ord-url"> checkout page: <? print $ord['url'] ?></div>
  <hr>
</div>
<h2>Shipping</h2>
<div class="mw-order-shipping-info mw-order-ord-<? print $ord['id'] ?>"> country <? print $ord['country'] ?> <br>
  city <? print $ord['city'] ?> <br>
  state <? print $ord['state'] ?> <br>
  zip <? print $ord['zip'] ?> <br>
  address <? print $ord['address'] ?> <br>
  address2 <? print $ord['address2'] ?> <br>
  phone <? print $ord['phone'] ?> <br>
</div>
<h2>Order information</h2>
<div class="mw-order-ord-id"> ORD: <? print $ord['id'] ?></div>
<div class="mw-order-ord-amount"> amount: <? print $ord['amount'] ?></div>
<div class="mw-order-ord-count"> items_count  : <? print $ord['items_count'] ?></div>
<br>
ORD: <? print $ord['id'] ?><br>
created_on: <? print $ord['created_on'] ?><br>
<br>
<? if(isarr($cart_items)) :?>
<? foreach ($cart_items as $item) : ?>
<div class="mw-order-item mw-order-item-<? print $item['id'] ?>">
  <div class="mw-order-item-id"> title: <? print $item['title'] ?></div>
  <div class="mw-order-item-amount"> price: <? print $item['price'] ?></div>
  <div class="mw-order-item-count"> items count: <? print $item['qty'] ?></div>
  <div class="mw-order-item-count"> total: <? print floatval($item['qty']) * floatval($item['price']) ?></div>
</div>
<? endforeach; ?>
<? else: ?>
<h2>The cart is empty?</h2>
<? endif;?>
