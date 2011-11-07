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

<table cellpadding="0" cellspacing="0" class="view_cart_table">
  <thead>
    <tr>
      <th width="320"> <?php  print (option_get('name', $params['module_id'])) ? option_get('name', $params['module_id']) : "Product name";  ?>
      </th>
      <th  width="320"><?php print option_get('details', $params['module_id']) ?></th>
      <th width="80"><?php print option_get('qty_text', $params['module_id']) ?></th>
      <th width="70"><?php print option_get('single_price_text', $params['module_id']) ?></th>
      <th width="45"><?php print option_get('remove_text', $params['module_id']) ?></th>
    </tr>
  </thead>
  <tbody>
    <? foreach($cart_items as $item): ?>
    <tr valign="middle" id="cart_item_row_<? print $item['id'] ?>">
      <td class="name_of_product"><? if(($item['to_table'] == 'table_content') and (intval($item['to_table_id']) > 0)): ?>
        <a  target="_blank" href="<? print post_link($item['to_table_id']) ?>"> <img src="<? print  thumbnail($item['to_table_id'], 120)  ?>" align="middle" style="float:left; padding:5px;"  /> <? print $item['item_name'] ?></a>
        <? else : ?>
        <? print $item['item_name'] ?>
        <? endif;?></td>
      <td><?  if(!empty($item['custom_fields'])) :  ?>
        <? foreach( $item['custom_fields'] as $cf): ?>
        <?   print ($cf['custom_field_name']);	?>
        :
        <? if(stristr($cf['custom_field_value'], '.jpg') or stristr($cf['custom_field_value'], '.png') or stristr($cf['custom_field_value'], '.gif')): ?>
        <img  height="16" src="<?   print ($cf['custom_field_value']);	?>" />
        <? else : ?>
        <?   print ($cf['custom_field_value']);	?>
        <? endif;?>
        <br />
        <?  endforeach;  ?>
        <? endif;?></td>
      <td class="product_qty"><select class="select_qty" name="qty" style="width: 38px;" id="qty_for_cart_item_id_<? print $item['id'] ?>" onchange="mw.cart.modify_item_properties('<? print $item['id'] ?>', 'qty', $(this).val());">
          <? if(intval($item['qty']) > 100) { $max = intval($item['qty']) + 50 ; } else { $max = 100; }  ?>
          <? for ($x = 1; $x <= $max; $x++) : ?>
          <option  <? if($item['qty'] == $x): ?> value="<? print $x; ?>" selected="selected" <? endif; ?>  ><? print $x; ?></option>
          <? endfor; ?>
        </select></td>
      <td class="product_price"><? print $item['price'] ?> <?php print option_get('shop_currency_sign') ; ?></td>
      <td class="product_remove"><a href="#" onclick="mw.cart.remove_item('<? print $item['id'] ?>');" class="remove_from_cart">&nbsp;</a></td>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>
<br />
<br />
Promo code:
<input  type="text" id="the_promo_code_input" value="<? print  CI::model ( 'cart' )->promoCodeGetCurent(); ?>"  onchange="mw.cart.check_promo_code()"   onblur="mw.cart.check_promo_code()"  />
<div id="the_promo_code_status"></div>
<br />
<br />
<div id="total"> 
  <?php  print (option_get('shipping_price_text', $params['module_id'])) ? option_get('shipping_price_text', $params['module_id']) : "Shipping";  ?>
  : </strong></span> <?php print option_get('shipping') ; ?> <?php print option_get('shop_currency_sign') ; ?> <br />
  <br />
  
  <span><strong>
  <?php  print (option_get('total_price_text', $params['module_id'])) ? option_get('total_price_text', $params['module_id']) : "Total price";  ?>
  : </strong></span> <b><span class="cart_items_total"><? print floatval(get_cart_total()) + floatval( option_get('shipping'));  ?></span> <?php print option_get('shop_currency_sign') ; ?></b> 
  
   </div>
<? else: ?>
<span class="cartico">The cart is empty. Please add some products.</span>
<? endif; ?>
<div class="c">&nbsp;</div>
