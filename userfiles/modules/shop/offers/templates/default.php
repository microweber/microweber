<?php
/*
type: layout
name: Default
description: Default offer template
*/
?>

<style>
.mw-price-value-offer {
	font-size: 34px;
	font-weight: 300;
	white-space: nowrap;
	color:red;
}
.mw-price-value-retail {
	font-size: 34px;
	font-weight: 300;
	white-space: nowrap;
	text-decoration:line-through;
	text-decoration-color: red;
	margin-left:10px;
}
.mw-price-expiry {
	margin-left:10px;
	color:red;
}
</style>

<script>
    //mw.moduleCSS("<?php print modules_url(); ?>shop/offers/styles.css");
</script>

<label class="mw-ui-check price-checkbox <?php if(!isset($params['data-in-stock']) or $params['data-in-stock'] == false){ print 'mw-disabled'; } ?> ">
  <input type="radio" class="no-post" name="prices" <?php if(!isset($params['data-in-stock']) or $params['data-in-stock'] == false){ print 'disabled'; } ?> value="<?php print $params['data-offer-price']; ?>" <?php if($params['data-count'] ==1 ){  print 'checked'; } ?> data-set="mw.cart.add('.mw-add-to-cart-<?php print $params['data-parent-id'] ?>','<?php print $params['data-offer-price']; ?>', '<?php print $params['data-title']; ?>');">
  <span></span> <span>
  <?php if(is_string($params['data-price-name']) and trim(strtolower($params['data-price-name'])) == 'price'): ?>
	<span class="mw-price-key"><?php _e($params['data-price-name']); ?></span>
  <?php else: ?>
	<span class="mw-price-key"><?php print $params['data-price-name']; ?></span>
  <?php endif; ?>
  : <span class="mw-price-value-offer"><?php print currency_format($params['data-offer-price']); ?></span><span class="mw-price-value-retail"><?php print currency_format($params['data-retail-price']); ?></span>
  <?php if(isset($params['data-expires'])){ ?>
	<span class="mw-price-expiry">ending: <?php print date_system_format($params['data-expires']);?></span>
  <?php } ?>
  </span>
</label>