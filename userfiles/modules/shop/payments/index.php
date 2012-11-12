<? $payment_options =  payment_options();  ?>
<? if(isarr($payment_options)) :?>
<script  type="text/javascript">
$(document).ready(function(){
 
 
 $('.mw-payment-gateway-<? print $params['id']; ?>').unbind('change');
  $('.mw-payment-gateway-<? print $params['id']; ?>').bind('change',function() {

	 $v = $(this).val();

	 
	 mw.$('.mw-payment-gateway-selected-<? print $params['id']; ?> .module:first').attr('data-selected-gw',$v);




	 mw.load_module('shop/payments/gateways/'+$v,'#mw-payment-gateway-selected-<? print $params['id']; ?>');




		 
	 });
 
 
});
</script>

<h3>Payment method</h3>
<select name="payment_gw" class="mw-payment-gateway mw-payment-gateway-<? print $params['id']; ?>">
  <? foreach ($payment_options as $item) : ?>
  <option value="<? print  $item['gw_file']; ?>"><? print  $item['title']; ?></option>
  <? endforeach; ?>
</select>
<div id="mw-payment-gateway-selected-<? print $params['id']; ?>">
  <module type="shop/payments/gateways/<? print $payment_options[0]['gw_file'] ?>"  />
</div>
<? endif;?>
