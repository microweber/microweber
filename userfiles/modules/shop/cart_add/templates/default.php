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
<script>mw.moduleCSS("<?php print modules_url(); ?>shop/cart_add/templates.css")</script>

<script>
    _AddToCartModalContent = window._AddToCartModalContent || function(title){
        var html = ''
        + '<section>'
          + '<h5>' + title + '</h5>'
          + '<p><?php _e("has been added to your cart"); ?></p>'
          + '<a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="mw-ui-btn"><?php _e("Continue shopping"); ?></a>'
          + '<a href="<?php print checkout_url(); ?>" class="mw-ui-btn mw-ui-btn-blue"><?php _e("Checkout"); ?></a></section>';
        return html;
    }
    _AddToCart = window._AddToCart || function(selector, id, title){
       mw.cart.add(selector, id, function(){
         mw.modal({
            content:_AddToCartModalContent(title),
            template:'mw_modal_basic',
            name:"AddToCartModal",
            width:400,
            height:200
         });
       });
    }
</script>
<br class="mw-add-to-cart-spacer" />
<module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price"  id="cart_fields_<?php print $params['id'] ?>"  />
<?php if(is_array($data)): ?>
<span class="price">
<?php $i=1 ;foreach($data  as $key => $v ): ?>
<div class="mw-price-item"> <span class="mw-price pull-left">
  <?php if(is_string($key) and trim(strtolower($key)) == 'price'): ?>
  <?php _e($key); ?>
  <?php else: ?>
  <?php print $key; ?>
  <?php endif; ?>
  : <?php print currency_format($v); ?></span>
  <?php if(!isset( $in_stock) or  $in_stock == false) : ?>
  <button class="btn btn-default pull-right" type="button" disabled="disabled" onclick="Alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered",true)); ?>');"><i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
  <?php _e("Out of stock"); ?>
  </button>
  <?php else: ?>
  <button class="btn btn-default pull-right" type="button" onclick="_AddToCart('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');"><i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
  <?php _e("Add to cart"); ?>
  </button>
  <?php $i++; endif; ?>
</div>
<?php if($i > 1) : ?>
<br />
<?php endif; ?>
<?php $i++; endforeach ; ?>
</span>
<?php endif; ?>
