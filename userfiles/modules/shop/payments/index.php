<?php $payment_options =  payment_options();  ?>
<script  type="text/javascript">
$(document).ready(function(){

    mw.$('.mw-payment-gateway-<?php print $params['id']; ?> input').commuter(function() {
        mw.$('.mw-payment-gateway-selected-<?php print $params['id']; ?> .module:first').attr('data-selected-gw',this.value);
        mw.load_module(''+this.value,'#mw-payment-gateway-selected-<?php print $params['id']; ?>');
    });

});
</script>



<?php if(is_array($payment_options)) :?>

<div class="well">
	<?php if(count($payment_options) > 0): ?>
	<h2 style="margin-top:0 " class="edit nodrop" field="checkout_payment_information_title" rel="global" rel_id="<?php print $params['id']?>">
		<?php _e("Payment method"); ?>
	</h2>
	<ul name="payment_gw" class="gateway-selector field-full mw-payment-gateway mw-payment-gateway-<?php print $params['id']; ?>">
		<?php $count = 0; foreach ($payment_options as $payment_option) : $count ++; ?>
		  
		<li>
			<label title="<?php print  $payment_option['name']; ?>">
				<input type="radio" <?php if($count == 1){  ?> checked="checked" <?php } ?> value="<?php print  $payment_option['gw_file']; ?>" name="payment_gw" />
				<?php if(isset($payment_option['icon'])) :?>
				<img src="<?php print  $payment_option['icon']; ?>" alt="" />
				<?php else : ?>
				<?php print  $payment_option['name']; ?>
				<?php endif;?>
			</label>
		</li>
	 
		<?php endforeach; ?>
	</ul>
	<?php endif;?>
	<div id="mw-payment-gateway-selected-<?php print $params['id']; ?>">
		<module type="<?php print $payment_options[0]['gw_file'] ?>"  />
	</div>
</div>
<?php else : ?>
<?php print lnotif("Click here to edit Payment Options"); ?>
<?php endif;?>
