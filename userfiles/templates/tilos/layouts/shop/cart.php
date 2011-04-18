<script type="text/javascript">
$(document).ready(function(){
    $(".select_qty").each(function(){
      for(var c=2; c<=50; c++){
        $(this).append("<option value='" + c+ "'>" + c + "</option>");
      }
    });
});
</script>

<div id="checkout">
  <? $is_empty = get_items_qty() ; ?>
  <? if($is_empty != 0): ?>
  <h2 class="title"><img src="<? print TEMPLATE_URL ?>img/cartlogo.jpg" style="margin-top: -6px;" align="right" alt="" />Finish your order</h2>
  <div id="cart"> <img src="<? print TEMPLATE_URL ?>img/hc.jpg" style="position: relative;top: 5px;" alt="" />&nbsp;&nbsp;You have <? print get_items_qty() ; ?> item in your cart
    <mw module="cart/items" />
    <? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
    <div id="finish"> <a href="<? print page_link($shop_page['id']); ?>"><img src="<? print TEMPLATE_URL ?>img/cshopping.jpg" align="left" /></a> <a href="<? print page_link($shop_page['id']); ?>/view:checkout"><img src="<? print TEMPLATE_URL ?>img/pvia.jpg" align="right" style="margin:16px 0 0 0; " /></a> <strong>OR</strong> </div>
  </div>
  <!-- /#cart -->
  <? else : ?>

  <? include "sidebar.php" ?>
  
  
  <h1><a href="<? print page_link($shop_page['id']); ?>">Your cart is empty. Click here to go to the products page.</a></h1>
  
  
  
  <? endif ?>
</div>
