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
  
 
<div class="webstore_mycart_container">
  <div class="webstore_mycart_blk">
    <div class="webstore_mycart_gray_tit_part">
      <div class="webstore_cart_icon"><img src="<? print TEMPLATE_URL ?>images/cart_icon.jpg" alt="cart" /></div>
      <div class="webstore_cart_tit">My Cart</div>
    </div>
    <? foreach($cart_items as $item): ?>
    <div class="webstore_cart_drop_record" id="cart_item_row_<? print $item['id'] ?>">
      <div class="webstore_cart_drop2">
        <select class="select_qty" name="qty"  id="qty_for_cart_item_id_<? print $item['id'] ?>" onchange="mw.cart.modify_item_properties('<? print $item['id'] ?>', 'qty', $(this).val()); ups_shiping_cost();">
          <? if(intval($item['qty']) > 100) { $max = intval($item['qty']) + 50 ; } else { $max = 100; }  ?>
          <? for ($x = 1; $x <= $max; $x++) : ?>
          <option  <? if($item['qty'] == $x): ?> value="<? print $x; ?>" selected="selected" <? endif; ?>  ><? print $x; ?></option>
          <? endfor; ?>
        </select>
      </div>
      <div class="webstore_cart_drop_lable" style="width:250px"><a href="<? print post_link($item['to_table_id']); ?>"><? print $item['item_name'] ?></a></div>
      <div class="webstore_cart_drop_lable">$<? print $item['price'] ?></div>
      <a href="#" onclick="mw.cart.remove_item('<? print $item['id'] ?>');  ups_shiping_cost();" id="webstore_cart_rec_del">delete</a> </div>
    <? endforeach; ?>
    
    
      <div class="webstore_totals">Items cost:&nbsp;&nbsp;&nbsp;$ <span class="cart_items_total"><? print floatval(get_cart_total());  ?></span> <?php print option_get('shop_currency_sign') ; ?> </div>
      
            <div class="webstore_totals">Shipping cost:&nbsp;&nbsp;&nbsp;<span class="cart_shipping_cost ups_shiping_cost"></span></div>
            
            
            
              <div class="webstore_totals webstore_sales_tax" style="display:none">Sales tax cost:&nbsp;&nbsp;&nbsp;<span class="sales_tax"></span></div>
                      

      
  
  </div>
</div>
<? else: ?>
<span class="cartico">The cart is empty. Please add some products.</span>
<? endif; ?>
