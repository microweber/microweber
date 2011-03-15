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

<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <th scope="col">Item name</th>
    <th scope="col">Qty</th>
    <th scope="col">Single price</th>
    <th scope="col">Total price</th>
  </tr>
  <? foreach($items as $item): ?>
  <tr>
    <td><h3><?  print $item['item_name']  ?>
    </h3>
    
    <?  if(!empty($item['custom_fields'])) :  ?>
    <br />

    <? foreach( $item['custom_fields'] as $cf): ?>
   
    
    <?   print ($cf['custom_field_name']);	?>:    <?   print ($cf['custom_field_value']);	?>    <br />
    <?  endforeach;  ?>
    <? endif;?>
    
    </td>
    <td><?  print $item['qty']  ?></td>
    <td><?  print $item['price']  ?></td>
    <td><?  print $item['price']*$item['qty']  ?></td>
  </tr>
  <?  endforeach;  ?>
</table>
