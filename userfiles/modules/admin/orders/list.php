<?

$data = array ();
$ord = CI::model ( 'cart' )->ordersGet ( $data, $limit = false );
							//p($ord);

?>
<script language="javascript">

function edit_order( $id){
	 data1 = {}
   data1.module = 'admin/orders/edit_order';
  
  
  if($id != undefined ){
	  
	   data1.id = $id;
	   
  }
 
	
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
 
   //$('#content_list').html(resp);
   mw.modal.close('order_items_modal');
   mw.modal.overlay();
   mw.modal.init({
     html:resp,
     id:'order_items_modal',
     height:'auto',
	  width:600,
     customPosition:{
       top:100,
       left:'center'
     }
   })

  }
    });
   
   

   
 
}

 

</script>

<h2>Orders history</h2>
<br />
<? if(!empty($ord)): ?>
<div class="bluebox">
  <div class="blueboxcontent">
    <table width="100%" border="1" cellspacing="1" cellpadding="1">
      <tr>
        <th scope="col">order_id</th>
        <th scope="col">amount</th>
        <th scope="col">names</th>
        <th scope="col">email</th>
        <th scope="col">country</th>
        <th scope="col">city</th>
        <th scope="col">zip</th>
        <th scope="col">address</th>
        <th scope="col">phone</th>
        <th scope="col">is_paid</th>
        <th scope="col">&nbsp;</th>
      </tr>
      <? foreach($ord as $item): ?>
      <tr id="order_id_<?  print $item['id']; ?>">
        <td><a href="#" onclick="edit_order('<?  print $item['id']; ?>')"><? print $item['order_id'] ; ?></a> | <a href="#" onclick="mw.cart.delete_order('<?  print $item['id']; ?>', '#order_id_<?  print $item['id']; ?>')">[x delete]</a></td>
        <td><? print $item['amount'] ; ?></td>
        <td><? print $item['names'] ; ?></td>
        <td><? print $item['email'] ; ?></td>
        <td><? print $item['country'] ; ?></td>
        <td><? print $item['city'] ; ?></td>
        <td><? print $item['zip'] ; ?></td>
        <td><? print $item['address'] ; ?></td>
        <td><? print $item['phone'] ; ?></td>
        <td><? print $item['is_paid'] ; ?></td>
        <td><? // p($item); ?></td>
      </tr>
      <? endforeach;  ?>
    </table>
  </div>
</div>
<div class="c" style="padding-bottom: 15px;"></div>
<? else: ?>
<div class="bluebox">
  <div class="blueboxcontent">
    <div class="richtext"> Your orders history is empty. </div>
  </div>
</div>
<div class="c" style="padding-bottom: 15px;"></div>
<? endif; ?>
