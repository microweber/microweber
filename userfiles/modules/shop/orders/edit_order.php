<?



$ord = get_orders($params['order-id']);
$cart_items = array();
if(isarr($ord[0])){
	$ord = $ord[0];
	$cart_items = get_cart('id='.$ord['id'].'&no_session_id=1');
	
	
} else {
	
error("Invalid order id");	
}

 
?>






<div id="mw-order-table-holder">

    <a class="mw-ui-btn right" href="<?php print admin_url(); ?>view:shop/action:orders"><span class="backico"></span><?php _e("Back to Orders"); ?></a>
  <h2><span style="color: #0D5C98"><? print $ord['id'] ?> |</span> <span class="font-12"><? print $ord['created_on'] ?></span> </h2>

<div class="mw-o-box mw-o-box-order-info">
  <div class="mw-o-box-header">
      <span class="ico iorder"></span><span>Order Information</span>
  </div>


  <? if(isarr($cart_items)) :?>

  <div class="mw-o-box-images">
      <?php for($i=0;$i<sizeof($cart_items);$i++){ ?>

      <?php } ?>
  </div>

  <table class="mw-o-box-table" cellspacing="0" cellpadding="0">
          <thead>
              <tr>
                  <th>Product Name</th>
                  <th>Price</th>
                  <th>Shipping Price</th>
                  <th>QTY</th>
                  <th>Promo Code</th>
                  <th>Total</th>
              </tr>
          </thead>
      <tbody>
          <?php $subtotal = 0; ?>
          <? foreach ($cart_items as $item) : ?>

          <?php
            $item_total = floatval($item['qty']) * floatval($item['price']);
            $subtotal = $subtotal + $item_total;
          ?>
          <tr class="mw-order-item mw-order-item-<? print $item['id'] ?>">
            <td class="mw-order-item-id"><? print $item['title'] ?></td>
            <td class="mw-order-item-amount"><? print $item['price'] ?></td>
            <td class="mw-order-item-amount"> shipping price: Ne se znae </td>
            <td class="mw-order-item-count"> <? print $item['qty'] ?></td>
            <td class="mw-order-item-amount"> promo ceode: Ne se znae </td>
            <td class="mw-order-item-count"><? print $item_total; ?></td>
          </tr>
          <? endforeach; ?>
          <tr class="mw-o-box-table-footer">
            <td colspan="4">&nbsp;</td>
            <td>Subtotal</td>
            <td class="mw-o-box-table-green"><?php print $subtotal; ?></td>
          </tr>
          <tr class="mw-o-box-table-footer">
            <td colspan="4">&nbsp;</td>
            <td>Promo Codes</td>
            <td class="mw-o-box-table-green">- $35,00</td>
          </tr>
          <tr class="mw-o-box-table-footer">
            <td colspan="4">&nbsp;</td>
            <td>Shipping price</td>
            <td class="mw-o-box-table-green">$20,00</td>
          </tr>
          <tr class="mw-o-box-table-footer last">
            <td colspan="4">&nbsp;</td>
            <td class="mw-o-box-table-green"><b>Total:</b></td>
            <td class="mw-o-box-table-green"><b>$120,00</b></td>
          </tr>
      </tbody>
  </table>
  <? else: ?>
  <h2>The cart is empty?</h2>
  <? endif;?>


 <div class="mw-o-box-header" style="background: none">
    <span class="ico iorder"></span><span>Order Status</span>
 </div>

 <div class="order-status-selector">
    <span class="font-11">What is the status of this order?</span>
    <label class="mw-ui-check">
      <input <?php if($ord['order_status']=='y'): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="y" />
      <span></span>
      <span>Completed Order</span>
    </label>
    <label class="mw-ui-check">
      <input <?php if($ord['order_status']=='n'): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="n"  />
      <span></span>
      <span>Pending</span>
    </label>
  </div>


<div class="vSpace">&nbsp;</div>


<script type="text/javascript">
  $(document).ready(function(){
     var obj = {
       id:"<?php print $ord['id']; ?>"
     }
     mw.$("input[name='order_status']").commuter(function(){
        obj.order_status = this.value;
        $.post(mw.settings.site_url+"api/update_order", obj, function(){
            mw.tools.el_switch(mwd.querySelectorAll('#mw_order_status .mw-notification'), 'semi');
        });
     });
  });
</script>




  <div id="mw_order_status">
    <div class="mw-notification mw-warning <?php if($ord['order_status']=='y'): ?>semi_hidden<?php endif; ?>">
      <div>
        Pending
      </div>
    </div>

    <div class="mw-notification mw-success <?php if($ord['order_status']=='n'): ?>semi_hidden<?php endif; ?>">
      <div>
        <span class="ico icheck"></span>
        <span>Successfully Completed</span>
      </div>
    </div>

 </div>

</div>



<div class="mw-o-box mw-o-box-client-info">
  <div class="mw-o-box-header">
      <a href="#" class="mw-ui-btn right"><?php _e("Edit"); ?></a>
      <span class="ico iusers"></span><span>Client Information</span>
  </div>
  <div class="mw-o-client-table">
      <table cellspacing="0" cellpadding="0">
          <tr>
              <td>Customer Name</td>
              <td><a href="#"><? print $ord['first_name'] . $ord['last_name']; ?></a></td>
          </tr>
          <tr>
              <td>Email</td>
              <td><a href="mailto:<? print $ord['email'] ?>"><? print $ord['email'] ?></a></td>
          </tr>
          <tr>
              <td>Phone Number</td>
              <td><b><? print $ord['phone'] ?></b></td>
          </tr>
      </table>
  </div>


  <div class="mw-o-box-hr"></div>


  <div class="mw-o-client-table">
      <table cellspacing="0" cellpadding="0">
          <tr>
              <td>
                <p><b>Shipping Address</b></p>
                <p>
                    <? print $ord['city'] ?> <br />
                    <? print $ord['state'] ?> <br />
                    <? print $ord['zip'] ?> <br />
                    <? print $ord['address'] ?> <br />
                    <? print $ord['address2'] ?> <br />
                    Phone <? print $ord['phone'] ?> <br />
                </p>
              </td>
              <td>
                  <center>
                    <iframe
                          width="185"
                          height="140"
                          frameborder="0"
                          scrolling="no"
                          marginheight="0"
                          marginwidth="0"
                          src="http://maps.yahoo.com/embed#zoom=15&mvt=m&trf=0&q=<? print $ord['address2']; ?>&conf=1&start=1">

                    </iframe>
                    <div class="vSpace"></div>
                    <a target="_blank" href="http://maps.yahoo.com#zoom=15&mvt=m&trf=0&q=<? print $ord['address2']; ?>&conf=1&start=1"><?php _e("See Location on map"); ?></a>
                 </center>

              </td>
          </tr>


      </table>
  </div>
  <div class="mw-o-box-hr"></div>

  <div class="mw-o-client-table">
      <table cellspacing="0" cellpadding="0">
          <tr>
              <td>
                <p><b>Billing Address</b></p>
                <p>
                    <? print $ord['city'] ?> <br />
                    <? print $ord['state'] ?> <br />
                    <? print $ord['zip'] ?> <br />
                    <? print $ord['address'] ?> <br />
                    <? print $ord['address2'] ?> <br />
                    Phone <? print $ord['phone'] ?> <br />
                </p>
              </td>
              <td>&nbsp;
                  
              </td>
          </tr>


      </table>
  </div>



<div class="mw-o-box-hr"></div>
<div class="mw-o-client-table">

  <p><b>Payment Information</b></p>

  Payment Method: <?php print $ord['payment_gw']; ?>  <br />
  Transaction ID: <?php print $ord['transaction_id']; ?>   <br />
  Payment Status: Verufied / Unknown         <br />

</div>


</div>


<div class="mw_clear"></div>








</div>

