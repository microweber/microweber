<script type="text/javascript">





$(document).ready(function() {

    cart_checkPromoCode()

	showShippingCost();

});



function cart_pre_checkout_update_the_total_price(){

$('#total_price_for_whole_cart').fadeOut();





$.ajax({

   type: "POST",

   async: false,

   url: "<?php print site_url('ajax_helpers/cart_itemsGetTotal'); ?>",

  //data: "name=John&location=Boston",

   success: function(data){

       $('#total_price_for_whole_cart').html(data); 

	   $('#total_price_for_whole_cart').fadeIn();

   }

 });





  

  

  

  

  

}







function cart_modify_item_properties($item_id_in_cart, $propery_name, $new_value){ 

$.ajax({

   type: "POST",

   url: "<?php print site_url('ajax_helpers/cart_ModifyItemProperties'); ?>",

   data: "id="+$item_id_in_cart+"&propery_name="+$propery_name+"&new_value="+$new_value,

   async: false,

   success: function(data){

		var single_price_for_cart_item_id = $('#single_price_for_cart_item_id_'+$item_id_in_cart).html();

		single_price_for_cart_item_id = parseFloat(single_price_for_cart_item_id);

		var qty_for_cart_item_id = $('#qty_for_cart_item_id_'+$item_id_in_cart).val();

		qty_for_cart_item_id = parseFloat(qty_for_cart_item_id);

		

		$('#total_price_for_cart_item_id_'+$item_id_in_cart).html(qty_for_cart_item_id*single_price_for_cart_item_id);

		update_the_cart_qty_in_header();

		cart_pre_checkout_update_the_total_price();

		cart_checkPromoCode();

		showShippingCost();



   }

 });





}





function cart_remove_item_from_cart($item_id_in_cart){ 

var answer = confirm("Are you sure you want to remove this item from your bag?")

	if (answer){

	

	

		$.ajax({

		type: "POST",

		async: false,

		url: "<?php print site_url('ajax_helpers/cart_removeItemFromCart'); ?>",

		data: "id="+$item_id_in_cart,

		success: function(data){

		

		 $('#item_row_'+$item_id_in_cart).remove();

			 

				

				

				$.post("<?php print site_url('ajax_helpers/cart_itemsGetQty'); ?>", { name: "John", time: "2pm" },

				function(data){

				data = parseInt(data);

				if(data == 0){

				alert("Your bag is now empty. We will take you to the shop, so you can add some items.");

				window.location="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>";

				} else {

				update_the_cart_qty_in_header();

				cart_pre_checkout_update_the_total_price();

				cart_checkPromoCode();

				showShippingCost();

				}

				});

		}

		});



	}

	else{

		

	}

 

}







function cart_checkPromoCode(){ 

//

var code_check = $('#the_promo_code_input').val();

 $.post("<?php print site_url('ajax_helpers/cart_getPromoCode'); ?>", { code: code_check, time: "2pm" },

  function(data){

  if(parseInt(data) != 0){

  var myPromoObject = eval('(' + data + ')');
 //var myPromoObject = eval( data);

  $('#the_promo_code_status').show();

  $('#the_promo_code_status').html(myPromoObject.description);

  

  $.post("<?php print site_url('ajax_helpers/cart_getTotal'); ?>", { name: "John", time: "2pm" },

  function(data2){

	//alert(data2);

	 var old_price = $('#total_price_for_whole_cart').html();

	 old_price = (old_price);

	 

	  var new_price =data2;

	  new_price = (new_price);

	  

	  if(old_price != new_price){

	  $('#total_price_for_whole_cart').css('textDecoration', 'line-through');

	  $('#new_price_for_whole_cart').html(new_price);

	  } else {

	    $('#total_price_for_whole_cart').css('textDecoration', 'none');

	   $('#new_price_for_whole_cart').html('');

	  }

  });

  

  

  

  

  

  } else {

  $('#the_promo_code_status').hide();

   $('#total_price_for_whole_cart').css('textDecoration', 'none');

	   $('#new_price_for_whole_cart').html('');

  }

  

	 //$('#total_price_for_whole_cart').html(data); 

	// $('#total_price_for_whole_cart').fadeIn();

  });



}











function changeShippingCountry(){



$c =  $('#shipping_county').val();



 $.post("<?php print site_url('ajax_helpers/set_session_vars'); ?>", { the_var:'country_name' , the_val: $c, time: "2pm" },

  function(data){

	 showShippingCost();

  });



}







function showShippingCost(){



 $.post("<?php print site_url('ajax_helpers/cart_shippingCalculateToCountryName'); ?>", { time: "2pm" },

  function(data){

	  

	  data = parseInt(data);

	  if(data != 0){

	  $('#shipping_price').html(data);

	  }

	 

  });



}





</script>

<!--<div style="width: 115px;" class="DropDown DropDownAlpha DropDownGray OBJDropDown right">



         <span>Currency: USD</span>

          <ul style="width: 115px;">

            <li class="active" title="USD">Currency: USD</li>

            <li class="active" title="EUR">Currency: EUR</li>

            <li class="active" title="GBP">Currency: GBP</li>

          </ul>

          <input type="hidden" name="currency" value="USD"/>

</div>-->

<?php $sid=$this->session->userdata ( 'session_id' );

$cart_item = array();

$cart_item['sid'] = $sid;

$cart_item['order_completed'] ='n';



$cart_items = CI::model ( 'cart' )->itemsGet($cart_item);

//var_dump($sid,$cart_items);    

 ?>

 

<?php if(!empty($cart_items)): ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td><div class="richtext" style="float: left;width: 200px;">.

        <h2 style="margin:0px;">Your order</h2>

      </div></td>

    <td><div style="width: 115px;" class="right"> <a href="javascript:cart_ajax_empty()">Empty your bag</a> </div></td>

  </tr>

</table>

<?php endif; ?>



<?php $no_promo = false; ?>



<div style="height:12px;clear: both"></div>

<?php if(!empty($cart_items)): ?>

<table id="checkout_table" cellpadding="0" cellspacing="0" width="929px">

  <thead>

    <tr>

      <th>SKU</th>

      <th>Item Name</th>

      <th>Item Size</th>

      <th>Item color</th>

      <th>Single Item Price</th>

      <th>QTY</th>

      <th>Final Price</th>

      <th>&nbsp;</th>

    </tr>

  </thead>

  <tbody>

    <?php foreach(($cart_items) as $item): ?>

    <?php if($item['skip_promo_code'] == 'y') { $no_promo = false; } ?>

    

    

    <tr id="item_row_<?php print $item['id'] ?>">

      <td><?php print $item['sku'] ?></td>

      <td><?php print $item['item_name'] ?></td>

      <td><?php print $item['size'] ?></td>

      <td><?php print $item['colors'] ?>

      

      

      </td>

      <td>

      <?php $this_item_price = (CI::model ( 'cart' )->currencyConvertPrice($item['price'], $this->session->userdata ( 'shop_currency' ))); ?>

      

      <div style="float:left"><?php print $this->session->userdata ( 'shop_currency_sign' ) ?> &nbsp;</div><div id="single_price_for_cart_item_id_<?php print $item['id'] ?>"><?php print $this_item_price ?></div></td>

      <td><select name="qty" id="qty_for_cart_item_id_<?php print $item['id'] ?>" onchange="cart_modify_item_properties('<?php print $item['id'] ?>', 'qty', this.value);">

          <?php for ($x = 1; $x <= 100; $x++) : ?>

          <option  <?php if($item['qty'] == $x): ?> value="<?php print $x; ?>" selected="selected" <?php endif; ?>  ><?php print $x; ?></option>

          <?php endfor; ?>

        </select>

      </td>

      <td><?php print $this->session->userdata ( 'shop_currency_sign' ) ?> <span id="total_price_for_cart_item_id_<?php print $item['id'] ?>"><?php print (($item['qty']) * (($this_item_price)) ); ?></span>

      

      

      <?php if($item['skip_promo_code'] != 'y'): ?>

       

	  

	  

	   <?php endif; ?>

      

      

      

      

      

      

      </td>

      <td><a title="Remove from bag" href="javascript:cart_remove_item_from_cart(<?php print $item['id'] ?>);" class="small_btn"><span>X</span></a></td>

    </tr>

    <?php endforeach; ?>

  <tfoot valign="top">

  <td>&nbsp;</td>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

    <td></td>

    <td></td>

    <td>&nbsp;</td>

    </tfoot>

    </tbody>

</table>

<br />

<table width="300" border="0" cellspacing="0" cellpadding="0" style="float: right" class="check_table">

  <tr valign="top">

    <th width="100" scope="row">Total price</th>

    <td><?php print $this->session->userdata ( 'shop_currency_sign' ) ?> <b><span id="total_price_for_whole_cart"><?php print (CI::model ( 'cart' )->itemsGetTotal(false,$this->session->userdata ( 'shop_currency' ))); ?></span></b>&nbsp; <b><span class="pink_text" id="new_price_for_whole_cart"></span></b> </td>

  </tr>

  <?php if($no_promo == false): ?>

  <tr width="100" valign="top">

    <th scope="row">Promo code</th>

    <td><div class="box" style="width: 120px"> 

        <input type="text" id="the_promo_code_input" onkeyup="cart_checkPromoCode()" value="<?php print $this->session->userdata ( 'cart_promo_code' ); ?>" style="padding: 2px 5px 2px 10px;width: 110px; border:0;background:none;" />

      </div>

      <small><span id="the_promo_code_status" class="pink_text" style="display:none; clear:both"></span></small> </td>

  </tr>

  <?php endif; ?>

 <tr>   

    <th scope="row">

    <?php $where_we_ship = CI::model ( 'cart' )->shippingGetActiveContinents(); ?>

    Shipping to   <?php $countries = $this->core_model->geoGetAllCountries($where_we_ship); ?>

            <select name="country" id="shipping_county" class="required" style="width:100px; font-size:10px; border:1px solid #b173b4;" onchange="changeShippingCountry()">

              <?php $i=0; foreach($countries as $c):   ?>

              <option value="<?php print $c['name'] ?>" <?php if($this->session->userdata ( 'country_name' ) == $c['name']) : ?> selected="selected" <?php endif;?> ><?php print $c['name'] ?></option>

              <?php $i++; endforeach; ?>

            </select></th>

    <td><?php print $this->session->userdata ( 'shop_currency_sign' ) ?> <strong><span id="shipping_price"></span></strong></td>

  </tr>  

</table>

<div style="height: 12px;overflow: hidden;clear: both"></div>

<table cellpadding="0" cellspacing="0" width="929px"> 

  <tr valign="top">

    <td align="left"><a class="big_btn left" href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>"> <span>Shop for more</span> </a></td>

    <td align="right"><a class="big_btn right" href="<?php print $link = CI::model ( 'content' )->getContentURLById(45); ?>"> <span>Continue to order</span> </a>

      <div class="clear"></div>

      <br />      . </td>

  </tr>

</table>

<br />

<br />

<br />

<br />

<?php else: ?>

Your bag is empty. Please go to the <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">shop</a> and add some items. <br />

<br />

<br />

<?php endif; ?>

