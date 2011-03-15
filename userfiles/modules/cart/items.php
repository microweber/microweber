<? $cart_items = get_cart_items();    ?>
<? if(!empty($cart_items)): ?>

<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th width="320">Name of the product</th>
      <th  width="320">Details</th>
      <th width="80">QTY</th>
      <th width="70">Single price</th>
      <th width="45">Remove</th>
    </tr>
  </thead>
  <tbody>
    <? foreach($cart_items as $item): ?>
    <tr id="cart_item_row_<? print $item['id'] ?>">
      <td class="name_of_product"><? print $item['item_name'] ?></td>
      
       <td> <?  if(!empty($item['custom_fields'])) :  ?>
 

    <? foreach( $item['custom_fields'] as $cf): ?>
   
    
    <?   print ($cf['custom_field_name']);	?>:    <?   print ($cf['custom_field_value']);	?>    <br />
    <?  endforeach;  ?>
    <? endif;?></td>
      
      
      
      
      <td class="product_qty"><select class="select_qty" name="qty" style="width: 38px;" id="qty_for_cart_item_id_<? print $item['id'] ?>" onchange="mw.cart.modify_item_properties('<? print $item['id'] ?>', 'qty', $(this).val());">
          <? if(intval($item['qty']) > 100) { $max = intval($item['qty']) + 50 ; } else { $max = 100; }  ?>
          <? for ($x = 1; $x <= $max; $x++) : ?>
          <option  <? if($item['qty'] == $x): ?> value="<? print $x; ?>" selected="selected" <? endif; ?>  ><? print $x; ?></option>
          <? endfor; ?>
        </select></td>
      <td class="product_price">$<? print $item['price'] ?></td>
      <td class="product_remove"><a href="#" onclick="mw.cart.remove_item('<? print $item['id'] ?>');" class="remove_from_cart">&nbsp;</a></td>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>
<div id="total"> <span><strong>Total price:</strong></span> <b>$ <span class="cart_items_total"><? print get_cart_total()  ?></span></b> </div>
<? else: ?>
<span class="cartico">The cart is empty. Please add some products.</span>
<? endif; ?>
<div class="c">&nbsp;</div>
