<h2 class="webstore_large_prod_title"><? print $post['content_title'] ?></h2>
<div class="webstore_large_prod_desc2">
  <div class="webstore_large_prod_img"> <img src="<? print thumbnail($post['id'], 349) ?>"  width="340"   /> </div>
  <div class="webstore_large_prod_desc">
    <p><b>Name:</b> <? print $post['content_title'] ?> <br />
    </p>
    <!--<p>These are the product's dimensions:<br />
					  </p>
					  <p>Each<br />
					  </p>
					  <p>7.88 in. wide x 2.38 in. high x 9.75 in. deep<br />
					    Volume: 182.86 cubic in.<br />
					    Weight: 1.80 lbs.<br />
					    <br />
					    <br />
					    <a href="#">Download PDF SPECIFICATION</a></p>-->
    <div class="webstore_large_prod_price_cart">
      <div class="webstore_large_prod_price_lable">PRICE:</div>
      <div class="webstore_prod_price_inner">$<? print cf_val($post['id'], 'price') ?></div>
      <form id="products_option_form_<? print $post['id'] ?>" method="post" action="#">
        <input type="hidden" value="<? print $post['id'] ?>"   name="post_id" />
        <input name="custom_field_price"   type="hidden" value="<? print cf_val($post['id'], 'price') ?>" />
      </form>
      <div class="webstore_addtocart_but_inner"><a href="javascript:add_to_cart_form('#products_option_form_<? print $post['id'] ?>')"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
    </div>
  </div>
</div>
<div class="webstore_large_prod_desc2 webstore_large_prod_desc_pad">



<editable rel="post" field="custom_field_body_edit">


<? $model =  cf_val($post['id'], 'model'); ?>

<? if($model  != false): ?>
<p>
  <h3>Model:</h3>
  <br />
  <? print html_entity_decode($model ) ?>
  </p>
 <?  endif; ?>
  
  
  
  
  <p>
  <h3>Description:</h3> <br />
  <? print $post['content_body'] ?> <br />
  </p>
 
 
 
 
 <? $dimensions =  cf_val($post['id'], 'dimensions'); ?>
  <? if($dimensions  != false): ?>
  <p>
  
  
  
  <h3>Dimensions:</h3>
  <br />
  <? print html_entity_decode($dimensions) ?>
  </p>
   <?  endif; ?>
  
  
  
  <? $specs =  cf_val($post['id'], 'specs'); ?>
  <? if($specs  != false): ?>
  <p>
  <h3>Specification:</h3>
  <br />
  <? print html_entity_decode( $specs) ?>
  </p>
   <?  endif; ?>
  
  
   
  <? $pdf =  cf_val($post['id'], 'pdf'); ?>
   
  
  <? if($pdf  != false and stristr($pdf, '.pdf' )): ?>
  <p>
  <h3>PDF:</h3>
  <br />
  <a href="<? print (html_entity_decode( $pdf)); ?>" target="_blank"><? print (html_entity_decode( $pdf)); ?></a>
  </p>
  <iframe src="http://docs.google.com/gview?url=<? print html_entity_decode( cf_val($post['id'], 'pdf')) ?>&embedded=true" style="width:650px; height:700px;" frameborder="0"></iframe>
  
  
  
   <?  endif; ?>
  
  
  
  
  </editable>
  
    
    <div class="webstore_large_prod_price_cart">
      <div class="webstore_large_prod_price_lable">PRICE:</div>
      <div class="webstore_prod_price_inner">$<? print cf_val($post['id'], 'price') ?></div>
      <form id="products_option_form_<? print $post['id'] ?>" method="post" action="#">
        <input type="hidden" value="<? print $post['id'] ?>"   name="post_id" />
        <input name="custom_field_price"   type="hidden" value="<? print cf_val($post['id'], 'price') ?>" />
      </form>
      <div class="webstore_addtocart_but_inner"><a href="javascript:add_to_cart_form('#products_option_form_<? print $post['id'] ?>')"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
    </div>
  
</div>
<? 


 
 
 
 
 
   
 
 
 
 
 
 
 
$c = CATEGORY_ID;
$products = get_posts("category={$c}&limit=4");
 

if(empty($products)){
	$t = "more products from this category";
	
	$c = PAGE_ID;
 	$products = 	get_posts("page={$c}&limit=4");
} else {
	$t = "More from <a href='". category_url($c). "'>". category_name($c). "</a>";;
}

//p($products);

?>
<div class="more_prods_holder">
<div class="more_prods"><? print $t; ?></div>
<? foreach($products['posts'] as $product): ?>
<div class="webstore_prod_box">
  <div class="webstore_prod_img"><a href="<? print post_link($product['id']); ?>"><img src="<? print thumbnail($product['id'], 349) ?>" width="161"  /></a> </div>
  <div class="webstore_prod_desc">
    <p><strong><? print $product['content_title'] ?></strong><br />
    </p>
    <? print character_limiter($product['content_description'], 30 )?> <br />
    <br />
    <form id="products_add_option_form_<? print $product['id'] ?>" method="post" action="#">
      <input type="hidden" value="<? print $product['id'] ?>"   name="post_id" />
      <input name="custom_field_price"   type="hidden" value="<? print cf_val($product['id'], 'price') ?>" />
    </form>
    <a href="<? print post_link($product['id']); ?>">More Details...</a> </div>
  <div class="webstore_prod_price">$<? print cf_val($product['id'], 'price') ?></div>
  <div class="webstore_addtocart_but"><a href="javascript:add_to_cart_form('#products_add_option_form_<? print $product['id'] ?>')"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
</div>
<? endforeach; ?>
</div>
<div class="more_prods_holder">
 <?  include TEMPLATE_DIR. "layouts/shop/crumbs.php"; ?>
 </div>
<!--<div class="webstore_prod_box">
  <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_87.jpg" width="161" height="117" /></div>
  <div class="webstore_prod_desc">
    <p><strong>CT-SLA</strong><br />
    </p>
    ConvTech, SWM Line Amp, 16 dB Gain, w/Ret &amp; 12V Power Supply <br />
    <br />
    <a href="#">More Details...</a> </div>
  <div class="webstore_prod_price">$78.00</div>
  <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
</div>-->




