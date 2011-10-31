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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="orders_table">
      <thead>
        <tr>
          <th scope="col">Order ID</th>
          <th scope="col">Amount</th>
          <th scope="col">Names</th>
          <th scope="col">Email</th>
          <th scope="col">Country</th>
          <th scope="col">City</th>
          <th scope="col">ZIP</th>
          <th scope="col">Date</th>
          <!-- <th scope="col comments_col"><span>Comments</span></th>-->
        </tr>
      </thead>
      <tbody>
        <? foreach($ord as $item): ?>
        <tr id="order_id_<?  print $item['id']; ?>">
          <td class="edit_order_cell"><div class="relative"> <a title="Delete Order" class="edit_order_delete" href="#" onclick="mw.cart.delete_order('<?  print $item['id']; ?>', '#order_id_<?  print $item['id']; ?>')">Delete</a> </div>
            <? /*
            <a href="#" onclick="edit_order('<?  print $item['id']; ?>')"><? print $item['order_id'] ; ?></a>
            */ ?>
            <a class="view_order_link" title="View Order" href="<? print site_url('admin/action:shop/view_order:');?><?  print $item['id']; ?>" ><? print $item['order_id'] ; ?></a></td>
          <td><? print $item['amount'] ; ?></td>
          <td><? print $item['names'] ; ?></td>
          <td><? print $item['email'] ; ?></td>
          <td><? print $item['country'] ; ?></td>
          <td><? print $item['city'] ; ?></td>
          <td><? print $item['zip'] ; ?></td>
          <td><? print $item['created_on'] ; ?></td>
          <!--  <td><? // p($item); ?></td>-->
        </tr>
        <? endforeach;  ?>
      </tbody>
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
