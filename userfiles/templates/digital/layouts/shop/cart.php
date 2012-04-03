<? $is_empty = get_items_qty() ; ?>
<? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
<? if($is_empty != 0): ?>

 <div class="cart_mid_holder">
 <mw module="cart/items" view="layouts/shop/cart_big.php" />

 
 
<mw module="cart/checkout" view="layouts/shop/checkout_info.php" />
 </div>
<!-- /#cart -->
<? else : ?>
<h1><a href="<? print page_link($shop_page['id']); ?>">Your cart is empty. Click here to go to the products page.</a></h1>
<? endif ?>
