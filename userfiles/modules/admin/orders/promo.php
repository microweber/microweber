<?

$data = array ();
$ord = CI::model ( 'cart' )->promoCodesGet ();
$ord[]  = array();
						//	 p($ord);

?>
<script type="text/javascript">
function toggle_promo_code_form($form_id){
	$($form_id).toggle();
	
}



function save_promo_code($form_id){
	 // data1 = ($('.'+$form_id).serialize());
	 
	  data1 = ($($form_id).serialize());
	 
	  $.ajax({
	  type: 'POST',
	  url: '<? print site_url('api/shop/promo_code_edit') ?>',
	  data: data1,
	   success: function(){
   mw.reload_module('admin/orders/promo');
   
   //mw.reload_module('content/custom_fields');
   
   
  } 
	});
	  
 
	

}


function delete_promo_code($id){
	 // data1 = ($('.'+$form_id).serialize());
	 
	 var r=confirm("Delete this promo code?");
if (r==true)
  {
  	  $.ajax({
	  type: 'POST',
	  url: '<? print site_url('api/shop/promo_code_delete') ?>',
	  data: 'id='+$id,
	   success: function(){
   mw.reload_module('admin/orders/promo');
   
   
   
   
  } 
	});
	  
  }
else
  {
 // alert("You pressed Cancel!");
  }
	 

 
	

}


</script>

<h2>Promo codes</h2>
<br />
<? if(!empty($ord)): ?>
<div class="bluebox">
  <div class="blueboxcontent">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="orders_table">
      <thead>
        <tr>
          <th scope="col">Code</th>
          <th scope="col">Discount amount</th>
          <th scope="col">Discount type</th>
          <th scope="col">description</th>
          <th scope="col">Edit</th>
          <!-- <th scope="col comments_col"><span>Comments</span></th>-->
        </tr>
      </thead>
      <tbody>
        <? foreach($ord as $item): ?>
        <tr id="order_id_<?  print $item['id']; ?>">
          <td>
		  <? if($item['auto_apply_to_all'] == 'y'):  ?>
        
		  <img src="<? print $config['url_to_module'] ?>star.png"  title="Applies this promo code to ALL orders automatically"/>
		  <? endif; ?>
		  
		  <? print $item['promo_code'] ; ?></td>
          <td><? print $item['amount_modifier'] ; ?></td>
          <td><? print $item['amount_modifier_type'] ; ?></td>
          <td><? print $item['description'] ; ?></td>
          <td>
		  
		  <a class="btn" href="javascript:toggle_promo_code_form('#promo<?  print $item['id']; ?>');"><? if(intval($item['id']) > 0): ?>Edit promo code<? else: ?>Add new promo code<? endif; ?></a>
       
            <form action="" method="post" id="promo<?  print $item['id']; ?>" style="display:none">
              <input name="id" type="hidden" value="<? print $item['id'] ; ?>" />
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td> Promo code</td>
                  <td><input name="promo_code" type="text" value="<? print $item['promo_code'] ; ?>" /></td>
                </tr>
                <tr>
                  <td>Discount amount</td>
                  <td><input name="amount_modifier" type="text" value="<? print $item['amount_modifier'] ; ?>" /></td>
                </tr>
                <tr>
                  <td>Discount type</td>
                  <td><select name="amount_modifier_type">
                      <option  value="amount"  <? if($item['amount_modifier_type'] == 'amount'):  ?> selected="selected" <? endif; ?>  >Fixed amount</option>
                      <option  value="percent"  <? if($item['amount_modifier_type'] == 'percent'):  ?> selected="selected" <? endif; ?>  >Percent</option>
                    </select></td>
                </tr>
                <tr>
                  <td>Description (shows more info to the user)</td>
                  <td><textarea name="description"><? print $item['description'] ; ?></textarea></td>
                </tr>
                <tr>
                  <td>Apply this promo code to ALL orders automatically <br />
                    <br />
                    <small> (you can have only one such promo code)</small></td>
                  <td><select name="auto_apply_to_all">
                      <option   <? if($item['auto_apply_to_all'] == 'n' or trim($item['auto_apply_to_all']) == ''):  ?> selected="selected" <? endif; ?>  value="n" >no</option>
                      <option   <? if($item['auto_apply_to_all'] == 'y'):  ?> selected="selected" <? endif; ?>  value="y" >yes</option>
                    </select></td>
                </tr>
                <tr>
                  <td><? if(intval($item['id']) > 0): ?>
                    <input name="delete" type="button" onClick="delete_promo_code('<?  print $item['id']; ?>')" value="delete" />
                    <? endif; ?></td>
                  <td><input name="save" type="button" onClick="save_promo_code('#promo<?  print $item['id']; ?>')" value="save" /></td>
                </tr>
              </table>
            </form></td>
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
