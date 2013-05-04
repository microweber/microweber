<?
only_admin_access();

$ord = get_orders('id='.$params['order-id']);

$cart_items = array();
if(isarr($ord[0])){
	$ord = $ord[0];
	$cart_items = get_cart('order_completed=y&session_id='.$ord['session_id'].'&order_id='.$ord['id'].'');


} else {

error("Invalid order id");
}


?>

<div id="mw-order-table-holder"> <a class="mw-ui-btn right" href="#vieworder=0"><span class="backico"></span>
  <?php _e("Back to Orders"); ?>
  </a>
  <h2><span style="color: #0D5C98"><?php print $ord['id'] ?> |</span> <span class="font-12"><?php print $ord['created_on'] ?></span> </h2>
  <div class="mw-o-box mw-o-box-order-info">
    <div class="mw-o-box-header"> <span class="ico iorder"></span><span>
      <?php _e("Order Information"); ?>
      </span> </div>
    <?php if(isarr($cart_items)) :?>
    <div class="mw-order-images">
      <?php for($i=0; $i<sizeof($cart_items); $i++){ ?>
      <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
      <?php if($p != false): ?>
      <img
            class="mw-order-item-image mw-order-item-image-<?php print $i; ?>"
            data-index="<?php print $i; ?>"
            src="<?php print thumbnail($p, 70,70); ?>"
        />
      <?php endif; ?>
      <?php } ?>
    </div>
    <table class="mw-o-box-table" cellspacing="0" cellpadding="0" width="100%">
      <thead>
        <tr>
          <th><?php _e("Product Name"); ?></th>
          <th><?php _e("Custom fields"); ?></th>
          <th><?php _e("Price"); ?></th>
          <th><?php _e("QTY"); ?></th>
          <?php /* <th><?php _e("Promo Code"); ?></th> */ ?>
          <th><?php _e("Total"); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php $subtotal = 0; ?>
        <?php $index = -1; foreach ($cart_items as $item) : ?>
        <?php
            $index++;
            $item_total = floatval($item['qty']) * floatval($item['price']);
            $subtotal = $subtotal + $item_total;
             $grandtotal = $subtotal + $ord['shipping'];
          ?>
        <tr
            data-index = "<?php print $index; ?>"
            class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>" >
          <td class="mw-order-item-id"><a href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a></td>
          <td class="mw-order-item-fields"><?php 	if(isset($item['custom_fields'])): ?>
            <?php print $item['custom_fields'] ?>
            <?php  endif ?></td>
          <td class="mw-order-item-amount"><?php print ($item['price']) ?></td>
          <td class="mw-order-item-count"><?php print $item['qty'] ?></td>
          <?php /* <td class="mw-order-item-amount"> promo ceode: Ne se znae </td> */ ?>
          <td class="mw-order-item-count" width="100"><?php print  currency_format($item_total, $ord['currency']); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="mw-o-box-table-footer">
          <td colspan="3">&nbsp;</td>
          <td><?php _e("Subtotal"); ?></td>
          <td class="mw-o-box-table-green"><?php print  currency_format($subtotal, $ord['currency']); ?></td>
        </tr>
        <?php /* <tr class="mw-o-box-table-footer">
          <td colspan="3">&nbsp;</td>
          <td>Promo Codes</td>
          <td class="mw-o-box-table-green">- $35,00</td>
        </tr> */ ?>
        <tr class="mw-o-box-table-footer">
          <td colspan="3">&nbsp;</td>
          <td><?php _e("Shipping price"); ?></td>
          <td class="mw-o-box-table-green"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></td>
        </tr>
        <tr class="mw-o-box-table-footer last">
          <td colspan="3">&nbsp;</td>
          <td class="mw-o-box-table-green"><b>
            <?php _e("Total:"); ?>
            </b></td>
          <td class="mw-o-box-table-green"><b><?php print  currency_format($grandtotal, $ord['currency']); ?></b></td>
        </tr>
      </tbody>
    </table>
    <?php else: ?>
    <h2>
      <?php _e("The cart is empty"); ?>
    </h2>
    <?php endif;?>
    <div class="vSpace"></div>
    <div class="mw-o-box-header" style="background: none;margin-bottom: 0;padding-bottom: 1px;"> <span class="ico iorder"></span><span>Order Status</span> </div>
    <div class="order-status-selector"> <span class="font-11">
      <?php _e("What is the status of this order"); ?>
      ?</span>
      <label class="mw-ui-check">
        <input <?php if($ord['order_status']=='completed' or $ord['order_status']==''): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="completed" />
        <span></span> <span>
        <?php _e("Completed Order"); ?>
        </span> </label>
      <label class="mw-ui-check">
        <input <?php if($ord['order_status']=='pending'): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="pending"  />
        <span></span> <span>
        <?php _e("Pending"); ?>
        </span> </label>
    </div>
    <div class="vSpace" style="padding: 0 0 1px;"></div>
    <script type="text/javascript">



  $(document).ready(function(){
           $(".mw-order-images img").bind("mouseenter mouseleave", function(e){
              var index = $(this).dataset('index');

              mw.tools.multihover(e, this, ".mw-order-item-index-"+index);
           });
           $("tr.mw-order-item").bind("mouseenter mouseleave", function(e){
              var index = $(this).dataset('index');
              mw.tools.multihover(e, this, ".mw-order-item-image-"+index);
           });

     var obj = {
       id:"<?php print $ord['id']; ?>"
     }
     mw.$("input[name='order_status']").commuter(function(){
        var val = this.value;
        obj.order_status = val;
        $.post(mw.settings.site_url+"api/update_order", obj, function(){
            mw.tools.el_switch(mwd.querySelectorAll('#mw_order_status .mw-notification'), 'semi');
            var states = {
              'y':'<?php _e("Completed"); ?>',
              'n':'<?php _e("Pending"); ?>',
            }
            mw.which(val, states, function(){
              mw.$(".mw-order-item-<?php print $ord['id']; ?> .mw-order-item-status").html(this.toString());
            });
        });
     });
  });
</script>
    <div id="mw_order_status" style="overflow: hidden">
      <div style="margin-right: 10px;width: 238px;" class="mw-notification mw-warning right <?php if($ord['order_status']=='completed'): ?>semi_hidden<?php endif; ?>">
        <div style="height: 55px;">
          <?php _e("Pending"); ?>
        </div>
      </div>
      <div style="margin-right: 10px;width: 238px;" class="mw-notification mw-success right <?php if($ord['order_status']!='completed'): ?>semi_hidden<?php endif; ?>">
        <div style="height: 55px;"> <span class="ico icheck"></span> <span>
          <?php _e("Successfully Completed"); ?>
          </span> </div>
      </div>
    </div>
  </div>
  <div class="mw-o-box mw-o-box-client-info">
    <div class="mw-o-box-header"> <a href="<?php print template_var('url'); ?>/../action:clients#?clientorder=<?php print $ord['id'] ?>" class="mw-ui-btn mw-ui-btn-medium right">
      <?php _e("Edit"); ?>
      </a> <span class="ico iusers"></span><span>
      <?php _e("Client Information"); ?>
      </span> </div>
    <div class="mw-o-client-table" style="padding-top: 0;">
      <table cellspacing="0" cellpadding="0">
        <col width="150" />
        <tr>
          <td><?php _e("Customer Name"); ?></td>
          <td><a href="#"><?php print $ord['first_name'] .' '. $ord['last_name']; ?></a></td>
        </tr>
        <tr>
          <td><?php _e("Email"); ?></td>
          <td><a href="mailto:<?php print $ord['email'] ?>"><?php print $ord['email'] ?></a></td>
        </tr>
        <tr>
          <td><?php _e("Phone Number"); ?></td>
          <td><b><?php print $ord['phone']; ?></b></td>
        </tr>
        <tr>
          <td><?php _e("User IP"); ?></td>
          <td><?php print $ord['user_ip']; ?>
            <?php if(function_exists('ip2country')): ?>
            <?php print ip2country($ord['user_ip']); ?>
            <?php endif; ?></td>
        </tr>
      </table>
    </div>
    <div class="mw-o-box-hr"></div>
    <div class="mw-o-client-table">
      <table cellspacing="0" cellpadding="0" class="right" style="width:400px">
        <col width="150" />
        <tr>
          <td valign="top"><p><b>
              <?php _e("Shipping Address"); ?>
              </b></p>
            <ul class="order-table-info-list">
              <li><?php print $ord['country'] ?></li>
              <li><?php print $ord['city'] ?></li>
              <li><?php print $ord['state'] ?></li>
              <li><?php print $ord['zip'] ?></li>
              <li><?php print $ord['address'] ?></li>
              <li><?php print $ord['address2'] ?></li>
              <li>
                <?php _e("Phone"); ?>
                <?php print $ord['phone'] ?></li>
            </ul></td>
          <td><a target="_blank" href="https://maps.google.com/maps?q=<?php print urlencode($ord['country'].','.$ord['address']) ?>&safe=off"> <img src="http://maps.googleapis.com/maps/api/staticmap?size=220x140&zoom=17&markers=icon:http://microweber.com/order.png|<?php print urlencode($ord['country'].','.$ord['address']); ?>&sensor=true&center=<?php print urlencode($ord['country'].','.$ord['address']); ?>" /> </a>
            <div class="vSpace"></div>
            <center>
              <a target="_blank" href="https://maps.google.com/maps?q=<?php print urlencode($ord['country'].','.$ord['address']) ?>&safe=off">
              <?php _e("See Location on map"); ?>
              </a>
            </center></td>
        </tr>
      </table>
    </div>
    <div class="mw-o-box-hr"></div>
    <div class="mw-o-client-table">
      <table class="right" cellspacing="0" cellpadding="0" style="width: 400px;">
        <col width="150" />
        <tr>
          <td valign="top"><p><b><?php _e("Billing Details"); ?></b></p>
            <ul class="order-table-info-list">
              <li><?php print $ord['payment_name'] ?></li>
              <li><?php print $ord['payment_country'] ?></li>
              <li><?php print $ord['payment_email'] ?></li>
              <li><?php print $ord['payment_city'] ?></li>
              <li><?php print $ord['payment_state'] ?></li>
              <li><?php print $ord['payment_zip'] ?></li>
              <li><?php print $ord['payment_address'] ?></li>
            </ul></td>
          <td  valign="top"><img src="http://maps.googleapis.com/maps/api/staticmap?size=220x140&zoom=17&markers=icon:http://microweber.com/user.png|<?php print urlencode($ord['payment_country'].','.$ord['payment_city'].','.$ord['payment_address']); ?>&sensor=true&center=<?php print urlencode($ord['payment_country'].','.$ord['payment_city'].','.$ord['payment_address']); ?>" />
            <center>
              <a target="_blank" href="https://maps.google.com/maps?q=<?php print urlencode($ord['payment_country'].','.$ord['payment_city'].','.$ord['payment_address']); ?>&safe=off">
              <?php _e("See Location on map"); ?>
              </a>
            </center></td>
        </tr>
      </table>
    </div>
    <div class="mw-o-box-hr"></div>
    <div class="mw-o-client-table">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td><p><b>
              <?php _e("Payment Information"); ?> 
              </b><span class="mw-help" data-help="Payment Information that we have from the payment provider">(?)</span></p>
            <ul class="order-table-info-list">
              <li>
                <?php _e("Payment Method"); ?>: <?php print $ord['payment_gw']; ?></li>

              <?php if(isset($ord['is_paid']) and $ord['is_paid'] == 'y'): ?>
                 <li><?php _e("Is Paid"); ?>: Yes</li>
              <?php else: ?>
                  <li><?php _e("Is Paid"); ?>: No</li>
              <?php endif; ?>

              <?php if(isset($ord['transaction_id']) and $ord['transaction_id'] != ''): ?>
              <li><?php _e("Transaction ID"); ?>: <?php print $ord['transaction_id']; ?></li>
              <?php endif; ?>
<?php if(isset($ord['payment_amount']) and $ord['payment_amount'] != ''): ?>
              <li>
                <?php _e("Payment Amount"); ?>: <?php print $ord['payment_amount']; ?><?php if(isset($ord['payment_shipping']) and $ord['payment_shipping'] != ''): ?> <span>+ <?php print $ord['payment_shipping']; ?> <?php _e("for shipping"); ?></span><?php endif; ?> <span class="mw-help" data-help="Amount paid by the user">(?)</span></li>
              <?php endif; ?>
              
             
               
             
              
              
              
              
              
<?php if(isset($ord['payment_currency']) and $ord['payment_currency'] != ''): ?>
              <li>
                <?php _e("Payment currency"); ?>: <?php print $ord['payment_currency']; ?></li>
              <?php endif; ?>
<?php if(isset($ord['payer_id']) and $ord['payer_id'] != ''): ?>
              <li>
                <?php _e("Payer ID"); ?>: <?php print $ord['payer_id']; ?></li>
              <?php endif; ?>

<?php if(isset($ord['payment_status']) and $ord['payment_status'] != ''): ?>
              <li>
                <?php _e("Payment Status"); ?>: <?php print $ord['payment_status']; ?></li>
              <?php endif; ?>


            </ul></td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
  </div>
  <div class="mw_clear"></div>
</div>
