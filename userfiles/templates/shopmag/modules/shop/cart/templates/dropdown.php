<?php

/*

type: layout

name: Dropdown cart

description: Dropdown cart template

*/

?>



<div class="mw-cart mw-dopdown-cart-content mw-cart-<?php print $params['id']?> <?php print $template_css_prefix  ?>">

<?php if(is_array($data)) :?>
<div class="item-box">
<div class="mw-dopdown-cart-container">


  <table class="mw-ui-table mw-ui-table-basic">

    <tbody>
      <?php foreach ($data as $item) : ?>
      <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
        <td class="mw-cart-table-product-image">
          <?php $pic = get_picture($item['rel_id']); ?>
            <img src="<?php print thumbnail($pic, 70,70); ?>" alt="" />
        </td>
        <td class="mw-cart-table-product">

        <?php
            $substrTitle = substr($item['title'], 0, 20);
            if($substrTitle != $item['title']){
                $substrTitle = $substrTitle. '...';
            }
        ?>

            <?php print $substrTitle; ?>

          <?php if(isset($item['custom_fields'])): ?>
          <?php print $item['custom_fields'] ?>
          <?php  endif ?></td>
        <td><?php print $item['qty'] ?></td>
        <td class="mw-cart-table-price"><?php print currency_format($item['price']* $item['qty']); ?></td>
        <td><a data-tip="<?php _e("Remove"); ?>" class="mw-icon-close show-on-hover tip" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"></a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php  
      if(!isset($params['checkout-link-enabled'])){
    	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
      }
      else {
    	   $checkout_link_enanbled = $params['checkout-link-enabled'];
      }
   ?>
  <?php if($checkout_link_enanbled != 'n') :?>
  <?php $checkout_page =get_option('data-checkout-page', $params['id']); ?>
  <?php

   if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   $checkout_page_link = content_link($checkout_page).'/view:checkout';
   } else {
	   $checkout_page_link = site_url('checkout');;
   }

   ?>

  <?php endif ; ?>

  </div>

  <a class="mw-dopdown-cart-footer" href="<?php print $checkout_page_link; ?>"><span class="sm-icon-bag2"></span><span><?php _e("Go to checkout"); ?></span></a>


</div>

<?php else : ?>
   <div class="item-box">
<div class="mw-dopdown-cart-container">
   <table class="mw-ui-table mw-ui-table-basic">
    <tr>
        <td style="white-space: nowrap">
            <h3 style="padding: 5px 12px;font-weight:100;">Your cart is empty</h3>
        </td>
    </tr>
   </table>



</div>
</div>
  <?php endif ; ?>
</div>
