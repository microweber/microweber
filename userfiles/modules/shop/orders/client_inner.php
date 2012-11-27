<? if(isset($params['order-id']) == true): ?>
<? 
$client = get_orders('one=1&id='.intval($params['order-id']));
$orders = get_orders('order_by=created_on desc&is_paid=y&email='.$client['email']);
 ?>
 
<div class="mw-admin-wrap">
  <h1>Clients Inner</h1>
  <div class="mw-o-box">
    <div class="mw-o-box-header"> <span class="ico iusers"></span> <span>Client Information</span> </div>
    Shipping information
    <table border="2" width="100%">
      
        <tr>
        <th scope="col"><?php _e("Names"); ?></th>
        <th scope="col"><?php _e("Email"); ?></th>
        <th scope="col"><?php _e("Phone"); ?></th>
        <th scope="col"><?php _e("Country"); ?></th>
        <th scope="col"><?php _e("City"); ?></th>
        <th scope="col"><?php _e("State"); ?></th>
        <th scope="col"><?php _e("Zip"); ?></th>
         </tr>
      <tr>
        <td><input type="text" name="first_name" value="<? print $client['first_name'] ?>" />
          <input type="text" name="last_name" value="<? print $client['last_name'] ?>" /></td>
        <td><input type="text" name="email" value="<? print $client['email'] ?>" /></td>
        <td><input type="text" name="phone" value="<? print $client['phone'] ?>" /></td>
        <td><input type="text" name="country" value="<? print $client['country'] ?>" /></td>
        <td><input type="text" name="city" value="<? print $client['city'] ?>" /></td>
        <td><input type="text" name="state" value="<? print $client['state'] ?>" /></td>
        <td><input type="text" name="zip" value="<? print $client['zip'] ?>" /></td>
      </tr>
       <tr>
        <td><?php _e("Address"); ?></td>
        <td colspan="5"><input type="text" name="address" value="<? print $client['address'] ?>" /></td>
      </tr>
      <tr>
        <td><?php _e("Address 2"); ?></td>
        <td colspan="5"><input type="text" name="address2" value="<? print $client['address2'] ?>" /></td>
      </tr>
    </table>
    Save btn
    <table border="2" width="100%">
      <tr>
        <td><pre><? print_r( $client); ?></pre></td>
        <td>2</td>
      </tr>
     
    </table>
  </div>
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <? if(isarr($orders )): ?>
  <? foreach($orders  as $item): ?>
  <div class="mw-o-box mw-o-box-accordion mw-accordion-active">
    <div class="mw-o-box-header"> <span class="ico iorder"></span>
      <div class="left">
        <h2><span style="color: #0D5C98"><? print $item['id'] ?> |</span><span class="font-12"><? print $item['created_on'] ?></span> </h2>
      </div>
      <span class="mw-ui-btn mw-ui-btn-small unselectable right" onmousedown="mw.tools.accordion(this.parentNode.parentNode);">Show Order <span class="mw-ui-arr"></span></span> <span class="hSpace right"></span> <a href="<? print template_var('url'); ?>/../action:orders#?vieworder=<? print $item['id'] ?>" class="mw-ui-btn mw-ui-btn-blue mw-ui-btn-small unselectable right"><span class="mw-ui-arr mw-ui-arr-left mw-ui-arr-blue "></span> Go to this order</a> </div>
    <div class="mw-o-box-accordion-content">
      <? $cart_items = get_cart('order_id='.$item['id'].'&no_session_id=1'); 	?>
      <? if(isarr($cart_items)): ?>
      <table cellspacing="0" cellpadding="0" class="mw-o-box-table" width="935">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>QTY</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <? foreach($cart_items  as $cart_item): ?>
          <tr class="mw-order-item mw-order-item-1">
            <td class="mw-order-item-id"><? print $cart_item['title'] ?></td>
            <td class="mw-order-item-amount"><? print $cart_item['price'] ?></td>
            <td class="mw-order-item-amount"><? print $cart_item['qty'] ?></td>
            <td class="mw-order-item-count"><? print $cart_item['price']*$cart_item['qty'] ?></td>
          </tr>
          <? endforeach ; ?>
        </tbody>
      </table>
      <? else : ?>
      Cannot get order's items
      <? endif; ?>
    </div>
  </div>
  <? endforeach ; ?>
  <? endif; ?>
</div>
<? else : ?>
Please set order-id parameter
<? endif; ?>
