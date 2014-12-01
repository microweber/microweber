<?php

/*

type: layout

name: Big

description: Full width cart template

*/

?>

<div class="mw-cart mw-cart-big mw-cart-<?php print $params['id']?> <?php print  $template_css_prefix;  ?>">
  <div class="mw-cart-title mw-cart-<?php print $params['id']?>">

  </div>
  <?php if(is_array($data)) :?>
  <div class="mw-ui-row-drop-on-1024" style="padding-bottom: 50px;">
      <div class="mw-ui-col"><table class="mw-ui-table mw-ui-table-basic">
    <colgroup>
        <col width="60">
        <col width="620">
        <col width="120">
        <col width="140">
        <col width="140">
    </colgroup>
    <thead>
      <tr>
        <th><?php _e("Image"); ?></th>
        <th class="mw-cart-table-product"><?php _e("Product Name"); ?></th>
        <th><?php _e("QTY"); ?></th>
        <th><?php _e("Price"); ?></th>
        <th><?php _e("Total"); ?></th>
        <th><?php _e("Delete"); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
       $total = 0;
       foreach ($data as $item) :
       $total += $item['price']* $item['qty'];
       ?>
      <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
      	 <?php $p = get_picture($item['rel_id']); ?>
         <td>
      <?php if($p != false): ?>
      <img src="<?php print thumbnail($p, 120,120); ?>"  />
      <?php endif; ?>
      </td>
        <td class="mw-cart-table-product">
	      <h3><?php print $item['title'] ?></h3>
          <?php if(isset($item['custom_fields'])): ?>
            <?php print $item['custom_fields'] ?>
          <?php  endif ?>
        </td>
        <td><input type="number" min="1" class="mw-ui-field quantity" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)" /></td>
        <?php /* <td><?php print currency_format($item['price']); ?></td> */ ?>
        <td class="mw-cart-table-price"><?php print currency_format($item['price']); ?></td>
        <td class="mw-cart-table-price"><?php print currency_format($item['price']* $item['qty']); ?></td>
        <td class="text-center"><a data-tip="<?php _e("Remove"); ?>" class="mw-icon-close show-on-hover tip" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"></a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table></div>

         <?php  $shipping_options =  api('shop/shipping/shipping_api/get_active'); ?>
	<?php

	$show_shipping_info =  get_option('show_shipping', $params['id']);

	if($show_shipping_info === false or $show_shipping_info == 'y'){
	    $show_shipping_stuff = true;
	} else {
	    $show_shipping_stuff = false;
	}

	 if(is_array($shipping_options)) :?>

     <?php if(get_option('shop_require_registration', 'website') == 'y' and is_logged() == false): ?>

     <?php else: ?>
    <div class="mw-ui-col" style="width: 340px;">
    <div class="order-summary-table-holder">
        <table cellspacing="0" cellpadding="0" class="order-summary-table" width="100%">
            <style scoped="scoped">
                td{
                  white-space: nowrap;
                }
                .checkout-total-table{
                  table-layout: fixed;
                }
                .checkout-total-table label{
                  display: block;
                  text-align: right;
                }

                .cell-shipping-total, .cell-shipping-price{
                  text-align: left;
                }

            </style>
            <tbody>
                <?php if( !empty($shipping_options) ){ ?>
                <tr <?php if(!$show_shipping_stuff) :?> style="display:none" <?php endif ; ?>>
                    <td class="cell-shipping-country">
                        <label><?php _e("Shipping to"); ?>:</label>
                    </td>
                    <td class="cell-shipping-country">
                        <module type="shop/shipping"  view="select" />
                    </td>
                </tr>
                <tr>
                    <td><label><?php _e("Shipping price"); ?>:</label></td>
                    <td  class="cell-shipping-price"><div class="mw-big-cart-shipping-price" style="display:inline-block"><module type="shop/shipping"  view="cost" /></div></td>
                </tr>
                <?php } ?>
                <tr>
                   <td><label><?php _e("Total Price"); ?>:</label></td>
                   <td  class="cell-shipping-total"> <span class="total_cost"><?php print currency_format($total + intval(mw('user')->session_get('shipping_cost'))); ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    <?php endif; ?>

  <?php endif ; ?>

  </div>


  <?php
      if(!isset($params['checkout-link-enabled'])){
    	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
      } else {
    	   $checkout_link_enanbled = $params['checkout-link-enabled'];
      }
  ?>
  <?php if($checkout_link_enanbled != 'n') :?>
  <?php $checkout_page =get_option('data-checkout-page', $params['id']); ?>
  <?php if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   $checkout_page_link = content_link($checkout_page).'/view:checkout';
   } else {
	   $checkout_page_link = site_url('checkout');;
   }
   ?>
  <a class="btn  btn-warning pull-right" href="<?php print $checkout_page_link; ?>"><?php _e("Checkout"); ?></a>

  

  <?php endif ; ?>
  <?php else : ?>
  <h4><?php _e("Your cart is empty."); ?></h4>
    <?php $shop_page = get_content('is_shop=0');      ?>
    <?php if (is_array($shop_page)): ?>
        <hr>
        <a href="<?php print page_link($shop_page[0]['id']); ?>" class="mw-ui-btn pull-left"><?php _e("Continue Shopping"); ?></a>
    <?php endif; ?>
  <?php endif ; ?>
</div>
