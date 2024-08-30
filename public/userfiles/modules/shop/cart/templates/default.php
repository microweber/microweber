<?php

/*

type: layout

name: Default

description: Default cart template

*/

?>
<div class="mw-cart mw-sidebar-cart mw-cart-<?php print $params['id']?> <?php print  $template_css_prefix  ?>">

  <?php if(is_array($data)) :?>
  <table class="table table-bordered table-striped mw-cart-table mw-cart-table-medium">
    <thead>
      <tr>
        <th class="mw-cart-table-product"><?php _e("Product Name"); ?></th>
        <th><?php _e("Qty"); ?></th>
        <th><?php _e("Total"); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $item) : ?>
      <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
        <td class="mw-cart-table-product"><?php print $item['title'] ?>
          <?php 	if(isset($item['custom_fields'])): ?>
          <?php print $item['custom_fields'] ?>
          <?php  endif ?></td>
        <td><input type="number" min="1" class="input-mini form-control input-sm" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value);" /></td>
        <?php /*<td><?php print currency_format($item['price']); ?></td>*/ ?>

        <td class="mw-cart-table-price"><?php print currency_format($item['price']* $item['qty']); ?></td>
        <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"></a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


  <?php
  if(!isset($params['checkout-link-enabled'])){
	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
  } else {
	   $checkout_link_enanbled = $params['checkout-link-enabled'];
  }
   ?>
  <?php if($checkout_link_enanbled != 'n') :?>
  <?php $checkout_page =get_option('data-checkout-page', $params['id']); ?>

      <?php if($checkout_page_link) :?>

  <a class="btn btn-default pull-right" href="<?php print $checkout_page_link; ?>"><?php _e("Checkout"); ?></a>


      <?php endif ; ?>
  <?php endif ; ?>
  <?php else : ?>
       <h4 class="alert"><?php _e("Your cart is empty"); ?>.</h4>
  <?php endif ; ?>
</div>
