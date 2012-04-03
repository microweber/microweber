<?
if($params['module_id'] == false){
$params['module_id'] = 'default';	
}
?>
<script type="text/javascript">
$(document).ready(function() {
mw.cart.check_promo_code();
});
</script>
<? $cart_items = get_cart_items();    ?>
<? if(!empty($cart_items)): ?>
<? foreach($cart_items as $item): ?>

<div class="webstore_cart_small_each_item">
  <div class="webstore_cart_drop" id="cart_item_row_<? print $item['id'] ?>">
    <select class="select_qty" name="qty" style="height:20px; padding-top:7px; height:30px;" id="qty_for_cart_item_id_<? print $item['id'] ?>" onchange="mw.cart.modify_item_properties('<? print $item['id'] ?>', 'qty', $(this).val());">
      <? if(intval($item['qty']) > 100) { $max = intval($item['qty']) + 50 ; } else { $max = 100; }  ?>
      <? for ($x = 1; $x <= $max; $x++) : ?>
      <option  <? if($item['qty'] == $x): ?> value="<? print $x; ?>" selected="selected" <? endif; ?>  ><? print $x; ?></option>
      <? endfor; ?>
    </select>
  </div>
  <div class="webstore_cart_drop_lable"> <? print $item['item_name'] ?> ($<? print $item['price'] ?>) </div>
</div>
<? endforeach; ?>
<div class="webstore_total">ITEMS: <span class="cart_items_qty"><? print get_items_qty() ; ?></span></div>
<div class="webstore_total">TOTAL:  $<span class="cart_items_total"><? print get_cart_total() ; ?></span></div>
<div class="complete_order_but"> <a href="<? print page_link(PAGE_ID); ?>/view:cart"><img src="<? print TEMPLATE_URL ?>images/complete_the_order_but.jpg" /></a> </div>
<? else: ?>
<span class="cartico">The cart is empty. Please add some products.</span>
<? endif; ?>
