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
  $title =  _e("Product");
}


?>
<script>mw.moduleCSS("<?php print MW_MODULES_URL; ?>shop/cart_add/templates.css")</script>
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
  <?php if(count($data) > 1){ ?>
  <?php $count = 0; foreach($data as $key => $v ): $count++; ?>
  <div class="mw-price-item"> <span class="mw-price">
    <label class="mw-ui-check price-checkbox <?php if(!isset( $in_stock) or  $in_stock == false){ print 'mw-disabled'; } ?> ">
      <input type="radio" class="no-post" name="prices" <?php if(!isset( $in_stock) or  $in_stock == false){ print 'disabled'; } ?> value="<?php print $v ?>" <?php if($count ==1 ){  print 'checked'; } ?> data-set="AddToCart('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
      <span></span> <span>
      <?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
      <span class="mw-price-key">
      <?php _e($key); ?>
      </span>
      <?php else: ?>
      <span class="mw-price-key"><?php print $key; ?></span>
      <?php endif; ?>
      : <span class="mw-price-value"><?php print currency_format($v); ?></span> </span> </label>
    </span> </div>
  <?php
          if($count == 1){
              $v_1 = $v;
           }
        ?>
  <?php endforeach ; ?>
  <div class="product-add-to-cart-holder">
    <button class="product-add-to-cart<?php if(!isset( $in_stock) or  $in_stock == false){ print ' mw-disabled'; }?>" type="button"

              <?php if(!isset( $in_stock) or  $in_stock == false){ ?> onclick="Alert('This Item is out of Stock');"<?php }  else { ?> onclick="AddToCart('.mw-add-to-cart-<?php print $params['id']; ?>','<?php print $v_1 ?>', '<?php print $title; ?>');" <?php } ?>>
    <span class="sm-icon-bag2"></span>
    <?php if(!isset( $in_stock) or  $in_stock == false){ ?>
    <?php _e("Out of stock"); ?>
    <?php } else{  ?>
    <?php _e("Add to cart"); ?>
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
    <?php else: ?>
    <span class="mw-price-key"><?php print $key; ?></span>
    <?php endif; ?>
    <span class="mw-price-value"><?php print currency_format($v); ?></span> </span> </div>
  <div class="product-add-to-cart-holder">
    <button class="product-add-to-cart" type="button"  <?php if(!isset( $in_stock) or  $in_stock == false){ ?> onclick="Alert('This Item is out of Stock');"<?php }  else { ?> onclick="AddToCart('.mw-add-to-cart-<?php print $params['id']; ?>','<?php print $v ?>', '<?php print $title; ?>');"<?php } ?>>
    <span class="sm-icon-bag2"></span>
    <?php if(!isset( $in_stock) or  $in_stock == false){ ?>
    <?php _e("Out of stock"); ?>
    <?php } else{  ?>
    <?php _e("Add to cart"); ?>
    <?php } ?>
    </button>
  </div>
  <?php endforeach; ?>
  <?php } ?>
</div>
<?php endif; ?>
