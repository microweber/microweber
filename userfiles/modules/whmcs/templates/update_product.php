<?php

/*

  type: layout

  name: Client products

  description: List the client products from WHMCS




*/

  ?>
<?php 
 

$product_id = false;

if(isset($params['product'])){
	$product_id = $params['product'];

} else if(isset($_REQUEST['update_product'])){
$product_id = $_REQUEST['update_product'];
}


$product_id = intval($product_id);
if($product_id == 0){
	return;
}

  $product = whm_get_user_products($product_id);

if(empty($product)){
	return;
}




 
$whmuser_info = whm_get_user_info();

 $goto = "viewinvoice.php?id=";

$goto = $whmuser_info['dologin'].'&goto='.urlencode($goto);

 
        
 
 
  ?>

<script type="text/javascript">
  mw.require('forms.js', true);
</script> 
	<script type="text/javascript">

    $(document).ready(function () {
        mw.$('.mw-change-plan-form').bind("submit",function () {
            mw.utils.stateloading(true);
            mw.form.post(mw.$('.mw-change-plan-form'), '<?php print site_url('api') ?>/whm_upgrade_user_product', function () {
                mw.response('.mw-change-plan-resp', this);

                if(this.result != undefined && this.result == 'success'){


					 mw.$('.mw-upgrade-plans-module').hide()



 					if(this.orderid == undefined && this.price != undefined){
					 mw.$('.mw-change-plan-resp').html( mw.$('#mw-change-plan-confirmation-copy').html());

							 mw.$('.mw-change-new-product-name').html(this.newproductname);
							mw.$('.mw-change-new-product-price').html(this.price);
							mw.$('.mw-change-new-product-cycle').html(this.newproductbillingcycle);
							 
		                }	
		            } else if(this.orderid != undefined && this.invoiceid != undefined){
						 mw.$('.mw-change-plan-resp').html( mw.$('#mw-change-plan-confirmation-thank-you').html());

							mw.$('.mw-change-new-product-name').html(this.newproductname);
							mw.$('.mw-change-new-product-price').html(this.price);
							mw.$('.mw-change-new-product-cycle').html(this.newproductbillingcycle);
							mw.$('.mw-change-new-product-orderid').html(this.orderid);
							mw.$('.mw-change-new-product-invoiceid').html(this.invoiceid);
 							window.location.href = "<?php print $goto ?>"+this.invoiceid;

							 
		                

		            }




				 mw.utils.stateloading(false);
            });
            return false;
        });

        mw.$(".choose-plan").click(function(){
             mw.$('.mw-change-plan-form').trigger("submit")
        });
    });
</script> 









  <h2>Change plan for <?php print $product['domain'] ?> </h2>

 <h4>Your current plan is <strong><?php print $product['name'] ?></strong> </h4>
<form class="mw-change-plan-form">

<input type="hidden" name="service_id" value="<?php print $product['id'] ?>">
   <module type="whmcs" template="choose_plan" current="<?php print $product['name'] ?>" class="mw-upgrade-plans-module" />


<div class="mw-change-plan-resp">
</div>
</form>



<div id="mw-change-plan-confirmation-copy" style="display:none">
	
		Please confirm you want to upgrade
	<input type="hidden" name="confirm_upgrade" value="true">

	<input type="submit" name="confirm_upgrade" value="true">

	<div class="mw-change-new-product-name">
	</div>
	<div class="mw-change-new-product-price">
	</div>

	<div class="mw-change-new-product-cycle">
	</div>

</div>



<div id="mw-change-plan-confirmation-thank-you" style="display:none">
	
		You have successfuly upgraded your account to 
	 
	<div class="mw-change-new-product-name">
	</div>
	<div class="mw-change-new-product-price">
	</div>

	<div class="mw-change-new-product-cycle">
	</div>

Your order ID is
		<div class="mw-change-new-product-orderid">
	</div>



	Your invoice ID is
		<div class="mw-change-new-product-invoiceid">
	</div>





 		<div class="mw-change-new-product-invoiceid">
 		</div>
 		<a class="btn btn-default btn-primary mw-pay-inv-link"  href="<?php   print($goto); ?>">Pay invoice</a>

	






</div>
