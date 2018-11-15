<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/
 ?>
<?php

if(isset($params['content-id'])){
  $product = get_content_by_id($params["content-id"]);
  $title =  $product['title'];
}
else{
  $title =  _e("Product",true);
}


?>
<script>mw.moduleCSS("<?php print modules_url();; ?>shop/cart_add/templates.css")</script>
<script>

$(mwd).ready(function(){
   mw.$(".price-checkbox").bind('click', function(){
        var val = mw.$('input', this).dataset('set');
        mw.$('.product-add-to-cart', mw.tools.firstParentWithClass(this, 'prices-holder')).attr('onclick', val);
   });
});

</script>
<module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price"  id="cart_fields_<?php print $params['id'] ?>"  />
<?php if(is_array($data)): ?>

<div class="prices-holder">
  <hr>
  <?php

  // if 'subscription-' in data key then get plan_id and display plan title in place of custom field title and interval after amount

	$ex_tax = '';

  	$price_subscriptions = false;
	foreach($data as $key => $v ){
		if(stristr($key,'subscription-')) {
			$price_subscriptions = true;
			break;
		}
 	}
 	if($price_subscriptions){
		// get subscription plan data
		$q = "SELECT * FROM payment_plans WHERE enabled='1' and obsolete='0'";
		$plans = mw()->database_manager->query($q);
		$subscriptions = array();
		$intervals = array();
		if(is_array($plans) && count($plans)>0){
			foreach($plans as $plan){
				$count = 0;
				foreach($data as $key => $v ){
					$count++;
					$price_plan_id = '';
					if(stristr($key,'subscription-')) {
						$price_plan_id = str_replace('subscription-','',$key);
						if(strtolower($plan['plan_id'])==strtolower($price_plan_id)){
							$subscriptions[$count] = $plan['plan_name'];
							$intervals[$count] = $plan['charge_interval'];
							break;
						}
					}
				}
			}
		}
 	}

	$taxes_enabled = get_option('enable_taxes', 'shop');
	if($taxes_enabled) {
		$defined_taxes = mw()->tax_manager->get();
		if(!empty($defined_taxes)) {
			if(count($defined_taxes) == 1) {
				$ex_tax = ' ex ' . $defined_taxes[0]['tax_name'];
			} else {
				$ex_tax = ' ex tax';
			}
		}
	}

	// check for offer prices
	if (mw()->modules->is_installed('shop/offers')) {
		$price_offers = offers_get_by_product_id($for_id);
	}
  ?>
  <?php if(count($data) > 1){ ?>
	  <?php $count = 0; foreach($data as $key => $v ): $count++; ?>
		  <div class="mw-price-item"> <span class="mw-price">

		  <?php
		  if (mw()->modules->is_installed('shop/offers') && is_array($price_offers) && isset($price_offers[$key])) {
		  ?>

		    <module type="shop/offers" data-content-id="<?php print intval($for_id); ?>" data-parent-id="<?php print $params['id']; ?>" data-title="<?php print $title; ?>" data-in-stock="<?php print $in_stock;?>" data-count="<?php print count($data);?>" data-price-name="<?php print $key;?>" data-offer-price="<?php print $price_offers[$key]['offer_price'];?>" data-retail-price="<?php print $v;?>" data-expires="<?php print $price_offers[$key]['expires_at'];?>" id="offer_price_<?php print $for_id . '_' . $price_offers[$key]['price_key'] ?>"  />

		  <?php } else { ?>

			<label class="mw-ui-check price-checkbox <?php if(!isset($in_stock) or $in_stock == false){ print 'mw-disabled'; } ?> ">
			  <input type="radio" class="no-post" name="prices" <?php if(!isset( $in_stock) or  $in_stock == false){ print 'disabled'; } ?> value="<?php print $v ?>" <?php if($count ==1 ){  print 'checked'; } ?> data-set="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
			  <span></span> <span>
			  <?php
				$interval = '';
				if($price_subscriptions):
					if(isset($subscriptions[$count])):
						$key = $subscriptions[$count];
						$interval = '<span class="mw-price-interval"> / '.$intervals[$count].$ex_tax.'</span>';
					endif;
				endif;
			  ?>
			  <?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
			  	<span class="mw-price-key"><?php _e($key); ?></span>
			  <?php else: ?>
			  	<span class="mw-price-key"><?php print $key; ?></span>
			  <?php endif; ?>
			  : <span class="mw-price-value"><?php print currency_format($v); ?></span><?php print $interval;?></span>
		      <?php //} ?>
		    </label>

		  <?php } ?>

		 </span> </div>
	  <?php
		  if($count == 1){
			  $v_1 = $v;
		  }
	  ?>
	  <?php endforeach ; ?>
	  <div class="product-add-to-cart-holder">
		<button class="product-add-to-cart<?php if(!isset( $in_stock) or  $in_stock == false){ print ' mw-disabled'; }?>" type="button"
		<?php if(!isset( $in_stock) or  $in_stock == false){ ?> onclick="Alert('This Item is out of Stock');"<?php }  else { ?> onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id']; ?>','<?php print $v_1 ?>', '<?php print $title; ?>');" <?php } ?>>
			<span class="sm-icon-bag2"></span>
			<?php if(!isset( $in_stock) or  $in_stock == false){ ?>
			<?php _e("Out of stock"); ?>
			<?php } else{  ?>
			<?php _e("Add to basket"); ?>
			<?php } ?>
		</button>
	  </div>

  <?php } else { ?>

	  <?php foreach($data as $key => $v ):  ?>
	  <div class="mw-price-item"> <span class="mw-price">
		<?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
			<span class="mw-price-key">
			<?php _e($key); ?>
			</span>
		<?php elseif(is_string($key) and trim(strtolower($key)) == 'subscription'): ?>
			<span class="mw-subscription-key">
			<?php _e($key); ?>
			</span>
		<?php else: ?>
			<span class="mw-price-key"><?php print $key; ?></span>
		<?php endif; ?>

<?php // TODO: add offers ?>

		<span class="mw-price-value"><?php print currency_format($v); ?></span> </span> </div>
	  <div class="product-add-to-cart-holder">
		<button class="product-add-to-cart" type="button"  <?php if(!isset( $in_stock) or  $in_stock == false){ ?> onclick="Alert('This Item is out of Stock');"<?php }  else { ?> onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id']; ?>','<?php print $v ?>', '<?php print $title; ?>');"<?php } ?>>
		<span class="sm-icon-bag2"></span>
		<?php
		if(!isset( $in_stock) or  $in_stock == false){
		 	_e("Out of stock");
		} else{
			_e("Add to basket");
		} ?>
		</button>
	  </div>
	  <?php endforeach; ?>

  <?php } ?>

</div>
<?php endif; ?>
