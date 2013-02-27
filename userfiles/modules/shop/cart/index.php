<script type="text/javascript">
mw.require("shop.js");
</script>
<?

$template = get_option('data-template', $params['id']);
$template_css_prefix = '';
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
  $template_css_prefix = no_ext($template);
    $template_file = module_templates($params['type'], $template);

//d();
}


?>
<?
$cart = array();
$cart['session_id'] = session_id();
$cart['order_completed'] = 'n';
 
 $data = get_cart($cart);
 //d($cart);
 ?>
<?php
switch ($template_file):
    case true:
        ?>
<? include($template_file); ?>
<?
        // d();

        if ($template_file != false) {
            break;
        }
        ?>
<?php
    case false:
        ?>

<div class="mw-cart mw-cart-<? print $params['id']?> <? print  $template_css_prefix  ?>">
  <div class="mw-cart-title mw-cart-<? print $params['id']?>">
    <h2 style="margin-top: 16px;">
      <?   _e('My cart') ?>
    </h2>
  </div>
  <? if(isarr($data)) :?>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>QTY</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($data as $item) : ?>
      <tr class="mw-cart-item mw-cart-item-<? print $item['id'] ?>">
        <td><? print $item['title'] ?>
          <? 	if(isset($item['custom_fields'])): ?>
          <? print $item['custom_fields'] ?>
          <?  endif ?></td>
        <td><input type="text" class="input-mini" value="<? print $item['qty'] ?>" onchange="mw.cart.qty('<? print $item['id'] ?>', this.value)" /></td>
        <td style="text-align: center"><? print currency_format($item['price']); ?></td>
        <td style="text-align: center"><? print currency_format($item['price']* $item['qty']); ?><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<? print $item['id'] ?>');"></a></td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>
  <?  
  if(!isset($params['checkout-link-enabled'])){
	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
  } else {
	   $checkout_link_enanbled = $params['checkout-link-enabled'];
  }
   ?>
  <? if($checkout_link_enanbled != 'n') :?>
  <? $checkout_page =get_option('data-checkout-page', $params['id']); ?>
  <? if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   
	   $checkout_page_link = content_link($checkout_page).'/view:checkout';
   } else {
	   $checkout_page_link = site_url().'?view=checkout';;
	   
   }
   
   ?>
  <a class="btn btn-warning right" href="<? print $checkout_page_link; ?>">Checkout</a>
  <? endif ; ?>
  <? else : ?>
  <div class="mw-cart-empty mw-cart-<? print $params['id']?>">
    <?   _e('Your cart is empty') ?>
  </div>
  <? endif ; ?>
</div>
<?php break; ?>
<?php endswitch; ?>
