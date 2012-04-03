
          
		  
		  <? include TEMPLATE_DIR. "layouts/shop/crumbs.php"; ?>

		  
		  
		  
		  
		  
		  <? if($post): ?>
          <? include TEMPLATE_DIR. "layouts/shop/inner.php"; ?>
           <? // include TEMPLATE_DIR. "layouts/shop/crumbs.php"; ?>
          <? else: ?>
          <? //p($active_categories) ?>
         


    
 

          
          <? foreach ($posts as $post) : ?>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><a href="<? print post_link($post['id']); ?>"> <img src="<? print thumbnail($post['id'], 160) ?>"   /></a> 
              <!--            <img src="<? print TEMPLATE_URL ?>images/webstore_prod_34.jpg" width="161" height="99" />
-->
            </div>
            <div class="webstore_prod_desc">
               <h3><a  href="<? print post_link($post['id']); ?>"><? print $post['content_title']; ?></a></h3><br />
              
              <? print character_limiter($post['content_body_nohtml'], 200); ?> <br />
              <br />
            <!--  <a class="shop_more_details_link" href="<? print post_link($post['id']); ?>">Product details</a>--> </div>
            <div class="webstore_prod_price">$<? print cf_val($post['id'], 'price') ?></div>
            <div class="webstore_addtocart_but">
              <form id="products_option_form_<? print $post['id'] ?>" method="post" action="#">
                <input type="hidden" value="<? print $post['id'] ?>"   name="post_id" />
                <input name="custom_field_price"   type="hidden" value="<? print cf_val($post['id'], 'price') ?>" />
              </form>
              <a href="javascript: add_to_cart_form('#products_option_form_<? print $post['id'] ?>')"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
              
                <a class="shop_more_details_link" href="<? print post_link($post['id']); ?>">Product details</a>
          </div>
          <? endforeach; ?>
          
          
          
          
          
          
                <div class="webstore_pagination">
            <table width="80%" border="0" cellspacing="5" cellpadding="5">
              <tr>
                <td width="100" align="right"> Pages: </td>
                <td align="left">
		 
				
				
				
				<? paging(); ?></td>
              </tr>
            </table>
            <!--  <ul>
              <li><a href="#"><<</a></li>
              <li><a href="#"><</a></li>
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#">></a></li>
              <li><a href="#">>></a></li>
            </ul>-->
          </div>
          <?  endif;?>
          <!-- <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_34.jpg" width="161" height="99" /></div>
            <div class="webstore_prod_desc">
              <p><strong>A150BGN</strong><br />
              </p>
              ReadyNet, 150Mbps Wireless USB Adaptor(1 Wireless Adaptor) <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$37.70</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_51.jpg" width="161" height="123" /></div>
            <div class="webstore_prod_desc">
              <p><strong>BLG-00036</strong><br />
              </p>
              S3, Universal TV Mount Smart Shelf, Black Glass, 36&quot; Wide <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$181.99</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_56.jpg" width="161" height="109" /></div>
            <div class="webstore_prod_desc">
              <p><strong>CT60S</strong><br />
              </p>
              ConvTech, HDMI over Cat5/6 Extender <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$126.46</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_72.jpg" width="161" height="109" /></div>
            <div class="webstore_prod_desc">
              <p><strong>CT-6PI</strong><br />
              </p>
              ConvTech, Power Inserter w/o Power Supply(250-2150 MHz) <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$130.00</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_87.jpg" width="161" height="117" /></div>
            <div class="webstore_prod_desc">
              <p><strong>CT-SLA</strong><br />
              </p>
              ConvTech, SWM Line Amp, 16 dB Gain, w/Ret &amp; 12V Power Supply <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$78.00</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_34.jpg" width="161" height="99" /></div>
            <div class="webstore_prod_desc">
              <p><strong>A150BGN</strong><br />
              </p>
              ReadyNet, 150Mbps Wireless USB Adaptor(1 Wireless Adaptor) <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$37.70</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_51.jpg" width="161" height="123" /></div>
            <div class="webstore_prod_desc">
              <p><strong>BLG-00036</strong><br />
              </p>
              S3, Universal TV Mount Smart Shelf, Black Glass, 36&quot; Wide <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$181.99</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_56.jpg" width="161" height="109" /></div>
            <div class="webstore_prod_desc">
              <p><strong>CT60S</strong><br />
              </p>
              ConvTech, HDMI over Cat5/6 Extender <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$126.46</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
            <div class="webstore_prod_img"><img src="<? print TEMPLATE_URL ?>images/webstore_prod_72.jpg" width="161" height="109" /></div>
            <div class="webstore_prod_desc">
              <p><strong>CT-6PI</strong><br />
              </p>
              ConvTech, Power Inserter w/o Power Supply(250-2150 MHz) <br />
              <br />
              <a href="#">More Details...</a> </div>
            <div class="webstore_prod_price">$130.00</div>
            <div class="webstore_addtocart_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/webstore_addtocart_but.jpg" alt="cart" border="0" /></a></div>
          </div>
          <div class="webstore_prod_box">
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
    
         
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
  