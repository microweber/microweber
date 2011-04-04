<?

$data = array ();
	 $data['id'] = $params['id'];
							 
							$ord = CI::model ( 'cart' )->ordersGet ( $data, $limit = false );
							
							
							//p($ord);
							
							
							$data = array ();
	 $data['order_id'] = $ord[0]['order_id'];
							
 
							
							$items = CI::model ( 'cart' )->itemsGet($data);
							//p($items);

?>


<div class="mw_box left" style="width: 48%">
<div class="mw_box_header">

 <h3>Order Information</h3>
 </div>

 <div class="mw_box_content">




<table width="100%" border="0" cellspacing="0" cellpadding="0" id="orders_table">
  <tr>
    <th scope="col">Item name</th>
    <th scope="col">Qty</th>
    <th scope="col">Single price</th>
    <th scope="col">Total price</th>
  </tr>


  <? foreach($items as $item): ?>
  <tr>
    <td class="edit_order_cell">

    <strong><?  print $item['item_name']  ?></strong>

    
    <?  if(!empty($item['custom_fields'])) :  ?>
    <br />

    <? foreach( $item['custom_fields'] as $cf): ?>
   

    <?   print ($cf['custom_field_name']);	?>:    <?   print ($cf['custom_field_value']);	?>    <br />
    <?  endforeach;  ?>
    <? endif;?>
    
    </td>
    <td><?  print $item['qty']  ?></td>
    <td><?  print $item['price']  ?></td>
    <td class="order_totals"><?  print $item['price']*$item['qty']  ?></td>
  </tr>
  <?  endforeach;  ?>

<tr class="orders_table_bord">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr><td></td><td></td><td>Subtotal:</td>         <td class="order_totals">$175,00</td></tr>
<tr><td></td><td></td><td>Promo Codes:</td>      <td class="order_totals">- $35,00</td></tr>
<tr><td></td><td></td><td>Shipping price:</td>   <td class="order_totals">$20,00</td></tr>

<tr><td></td><td></td><td>Total:</td>		      <td class="order_totals">$120,00</td></tr>


</table>









</div>

</div>






<div class="mw_box right" style="width: 48%">
<div class="mw_box_header">

 <h3>Custumar Information </h3>
 </div>

 <div class="mw_box_content">

       uhuuu

</div>

</div>


