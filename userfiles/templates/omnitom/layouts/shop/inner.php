<?php include(ACTIVE_TEMPLATE_DIR.'shop_side_nav.php') ;  ?>

<div id="main">
  <div id="product-profile" class="wrap">
    <h2><?php print $post['content_title'] ?></h2>
    <!--<p class="product-profile-description">-->
    <div class='product-profile-description'>
      <!-- </p>-->
      <div class="richtext"><?php print (($post['the_content_body']) ); ?></div>
      <div id="main_image_holder" class="wrap" style="">
        <?php $pictures = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($post['id'], '900');  
							$pictures_small1 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($post['id'], '500');
							$pictures_small2 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($post['id'], '75');
							//var_Dump($pictures);
							  ?>
        <div id="main_image" class="box boxv2" style="height:auto;text-align: left" > <a href="<?php print ($pictures[0]); ?>" id="main_magnify" class="zoom"><img src="<?php print $pictures_small1[0]; ?>" alt="" height="350" /></a></div>
        <ul>
          <?php $i = 0 ; foreach($pictures as $img): ?>
          <li><a href="<?php print $pictures[$i]; ?>" rel="<?php print $pictures_small1[$i]; ?>"
                                style="background-image:url('<?php print $pictures_small2[$i]; ?>')"></a></li>
          <?php $i++; endforeach; ?>
        </ul>
      </div>
      <script type="text/javascript">
        function only_colors(){
            $("span#only_colors div").remove();
            $("span#only_colors s").remove();
        }
        //setInterval("only_colors()", 100)

        $(function(){
                var m_width=$("#main_magnify img:first").width();
                $("#main_magnify img:first").css("left", 150-m_width/2);
            $(window).load(function(){
                var m_width=$("#main_magnify img:first").width();
                $("#main_magnify img:first").css("left", 150-m_width/2);
            });
        });

 $(document).ready(function() {

$("#product_color").change( function() {
	alert(($(this).val()));
  // check input ($(this).val()) for validity here
});
 });

      </script>
      <!-- /main_image_holder -->
      <form class="cart_ajax_form">
        <div id="product_profile_details" class="wrap">
        
           <?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $post['id']);
	$post['custom_fields'] = $more;
	?>
        
          <?php if(strval($post['custom_fields']['colors']) != ''):
	  $colors = explode(',',$post['custom_fields']['colors']); 
	  $colors = trimArray($colors);
	   ?>
          <?php if(!empty($colors)): ?>
          <div class="dtitled left" style="margin-right:45px"> <span class="dropdown_title">Colors:</span>
            <div id="objcolors" class="DropDown DropDownAlpha DropDownColors DropDownGray zebra"> <span id="only_colors"></span>
              <ul style="width:215px;height: 200px;overflow-x: hidden;overflow-y: scroll">
                <?php $i=0; foreach($colors as $color): ?>
                <?php $color_name = str_replace('_', ' ', $color); ?>
                <?php $color_name = str_replace('.png', '', $color_name); ?>
                <?php $color_name = ucwords($color_name); ?>
                <li <?php if($i == 0): ?>class="active" <?php endif; ?> title="<?php print $color_name; ?>">
                  <?php if($color_name != ''): ?>
                  <div style="clear: both;height:3px;overflow: hidden">
                    <!--  -->
                  </div>
                  <s> <img style="-display:none" src="<? print TEMPLATE_URL ?>image.php?width=40&amp;height=20&amp;image=<?php print base_url() ?>colors/<?php print $color; ?>"  alt="" />
                  <!--[if IE 6]><v:image style='width:40px;height:20px;display:block' src="http://omnitom.com/image.php?width=40&amp;height=20&amp;image=<?php print base_url() ?>colors/<?php print $color; ?>" /><![endif]-->
                  </s> <em><?php print $color_name; ?></em>
                  <?php endif; ?>
                  <div style="clear: both;height:3px;overflow: hidden">
                    <!--  -->
                  </div>

                                       <?php $color_sizes123 =  $post['custom_fields']['color_sizes_'. md5($color)]  ;
                                              $color_sizes123 = explode(',',$color_sizes123);

                                        ?>
                                        <?php if(!empty($color_sizes123)) :  ?>
                                              <ul style="display:none;position: absolute;visibility: hidden;" class="objcolorsSizes">
                                                  <?php foreach($color_sizes123 as $size_item): ?>
                                                       <li title="<?php print trim($size_item); ?>"><?php print trim($size_item); ?></li>
                                                  <?php endforeach; ?>
                                              </ul>


                                        <?php endif;  ?>





                </li>
                <?php $i++; endforeach; ?>
                <!-- <li title="purple" class="active"><s style="background:purple"></s><em>Purple</em><s style="background:red"></s><em>Red</em></li>
              <li title="green"><s style="background:green"></s><em>Green</em></li>
              <li title="yellow"><s style="background:yellow"></s><em>Green</em></li>
              <li title="red"><s style="background:red"></s><em>Green</em></li>-->
              </ul>
              <input name="colors" id="product_color" type="hidden" />
             
            </div>
          </div>
          <?php /*
          <div class="dtitled left" style="margin-right:45px"> <span class="dropdown_title">Colors:</span>
            <div class="DropDown DropDownAlpha DropDownColors DropDownGray zebra"> <span id="only_colors"></span>
              <ul style="width:215px;height: 200px;overflow-x: hidden;overflow-y: scroll">
                <?php $i=0; foreach($colors as $collorr): ?>
                <?php $colors_array = explode('+', $collorr); ?>
                <li <?php if($i == 0): ?>class="active" <?php endif; ?> title="<?php print $collorr; ?>">
                  <?php foreach($colors_array as $single_color) :
				  $single_color = trim($single_color);
				  ?>
                  <?php if($single_color != ''): ?>
                  <div style="clear: both;height:3px;overflow: hidden">
                    <!--  -->
                  </div>
                  <s style="background:<?php print $single_color ?>"></s> <em><?php print ucfirst($single_color ); ?></em>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <div style="clear: both;height:3px;overflow: hidden">
                    <!--  -->
                  </div>
                </li>
                <?php $i++; endforeach; ?>
                <!-- <li title="purple" class="active"><s style="background:purple"></s><em>Purple</em><s style="background:red"></s><em>Red</em></li>
              <li title="green"><s style="background:green"></s><em>Green</em></li>
              <li title="yellow"><s style="background:yellow"></s><em>Green</em></li>
              <li title="red"><s style="background:red"></s><em>Green</em></li>-->
              </ul>
              <input name="colors" type="hidden" />
            </div>
          </div>
          
          
          */ ?>
          <?php endif; ?>
          <?php endif; ?>
          
          
          
          
          
          
          
          
          
          
          
          
          <?php if(strval($post['custom_fields']['sizes']) != ''):
	  $sizes = explode(',',$post['custom_fields']['sizes']);  ?>
          <?php if(!empty($sizes)): ?>
          <div class="dtitled left" style="margin-right:45px"> <span class="dropdown_title">Size:<em>(<a href="<?php print $link = CI::model ( 'content' )->getContentURLById(56); ?>">size-chart</a>)</em></span>
            <div class="DropDown DropDownAlpha DropDownGray" style="width:100px" id="selectTheSize"><span>Select Size</span>
              <ul style="width:100px">
                <?php $i=0; foreach($sizes as $s):
		  $s = strtoupper($s);
		   ?>
                <li title="<?php print $s ?>"   <?php if($i == 0): ?>class="active" <?php endif; ?> ><?php print $s ?></li>
                <?php $i++; endforeach; ?>
              </ul>
              
              <input name="size" type="hidden" />
            </div>
          </div>
          <?php endif; ?>
          <?php endif; ?>
          
          
          
          
          
          
          
          
          
          
          
          
          <?php if($post['custom_fields']['in_stock'] != 'n') : ?>
          <div class="dtitled left" style="margin-right:0px; <?php if(trim($post['custom_fields']['external_product_order_link']) != '') :   ?> visibility:hidden; <?php endif; ?>   "> <span class="dropdown_title">QTY:</span>
            <div class="DropDown DropDownAlpha DropDownColors DropDownGray" style="width:50px" id="qty"> <span></span>
              <ul style="width:50px">
                <li title="1" class="active"><em>1</em></li>
                <li title="2"><em>2</em></li>
                <li title="3"><em>3</em></li>
                <li title="4"><em>4</em></li>
                <li title="5"><em>5</em></li>
                <li title="6"><em>6</em></li>
                <li title="7"><em>7</em></li>
                <li title="8"><em>8</em></li>
                <li title="9"><em>9</em></li>
                <li title="10"><em>10</em></li>
              </ul>
              <input name="qty" type="hidden" />
            </div>
          </div>
           <?php endif; ?>
          <div class="clear">
            <!--  -->
          </div>
         
          <div class="availability" style="<?php if(trim($post['custom_fields']['external_product_order_link']) != '') :   ?> visibility:hidden <?php endif; ?>" >Availability:
            <?php if($post['custom_fields']['in_stock'] != 'n') : ?>
            In stock
            <?php else: ?>
            Out of stock
            <?php endif; ?>
            <br />
            <?php if($post['custom_fields']['in_stock'] != 'n') : ?>
            <?php $stock_msg= $this->core_model->optionsGetByKey ( 'shop_in_stock_msg' ); ?>
            <?php print $stock_msg ?> 
            <?php else: ?>
            <?php if(trim($post['custom_fields']['out_of_stock_message']) != '') : ?>
            <?php $stock_msg= trim($post['custom_fields']['out_of_stock_message']); ?>
            <span class="red">
            <?php print $stock_msg ?> </span>
            <?php else: ?>
            <?php $stock_msg= $this->core_model->optionsGetByKey ( 'shop_out_of_stock_msg' ); ?>
            <span class="red"><?php print $stock_msg ?> </span>
            <?php endif; ?>
            <?php endif; ?>
            <br />
            </div>





          <?php $this_shp_item_price = (CI::model ( 'cart' )->currencyConvertPrice($post['custom_fields']['price'], $this->session->userdata ( 'shop_currency' ))); ?>
          <?php //print $this_shp_item_price; ?>
          <input name="price" value="<?php print $post['custom_fields']['price'] ?>" type="hidden" />
          
          

          <?php if(intval($post['custom_fields']['added_shipping']) != 0) : ?>
          <input name="added_shipping_price" value="<?php print  $post['custom_fields']['added_shipping']; ?>" type="hidden" />
          <?php endif; ?>
          
          
          <?php if(intval($post['custom_fields']['old_price']) != 0) : ?>
          
		   <input name="skip_promo_code" value="y" type="hidden" />
          
          
		  <?php endif; ?> 



          
          <input name="sku" value="<?php print $post['custom_fields']['sku'] ?>" type="hidden" />
          <input name="item_name" value="<?php print $post['content_title'] ?>" type="hidden" />
          <!-- <input name="currency" value="<?php print $this->session->userdata ( 'shop_currency' ) ?>" type="hidden" />-->
          <input name="weight" value="<?php print $post['custom_fields']['weight'] ?>" type="hidden" />
          <div class="cart-price" style="position: relative;zoom:1;height:65px;">
            <span <?php if(intval($post['custom_fields']['old_price']) != 0) : ?>class="hasOldPrice"<?php endif; ?>  id="price" title="<?php print $this_shp_item_price ?>"><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><strong><?php print $this_shp_item_price ?></strong></span>

            <?php if(intval($post['custom_fields']['old_price']) != 0) : ?>
             <s id="old-price"><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><?php print  ceil(CI::model ( 'cart' )->currencyConvertPrice($post['custom_fields']['old_price'], $this->session->userdata ( 'shop_currency' )));   ?></s>
           <?php endif; ?>


            <div class="clear" style="height: 5px;"></div>
            <?php if(trim($post['custom_fields']['external_product_order_link']) == '') :   ?>
             <?php if($post['custom_fields']['in_stock'] != 'n') : ?>
            <a href="javascript:cart_ajax_form_submit()" class="big_btn right" style="position: absolute;bottom:0px;right:0;"><span>Add to bag</span></a>
            
             <?php endif; ?>
            
            <?php else: ?>
            <a href="<?php print trim($post['custom_fields']['external_product_order_link']) ?>" class="big_btn right" style="position: absolute;bottom:0px;right:0;"><span>Order now</span></a>
            <?php endif; ?>
          </div>
          <div class="clear">
            <!--  -->
          </div>
          
          <div class="box spreadsheet" style="<?php if(trim($post['custom_fields']['external_product_order_link']) != '') :   ?> visibility:hidden <?php endif; ?>">
            <h3 class="check_title">Satisfaction guarantee</h3>
            <br />
            <table width="100%" border="0" cellspacing="5" cellpadding="2">
              <tr valign="middle">
                <td><div><img alt="organic cotton" src="<?php print TEMPLATE_URL; ?>img/sspoc2.jpg" /></div></td>
                <td>This item is made in the EU with a lot of love and care under the highest quality standards. If however you are not satisfied with our product you can exchange it for another or get your money back. Discounted items are non-refundable. All products are made of pure organic cotton. <br />
                  <br />
                  See the <a href="<?php print CI::model ( 'content' )->getContentURLById(26); ?>">terms and conditions</a> for more details.</td>
              </tr>
            </table>
          </div>
        </div>
         <input name="to_table_id" value="<?php print $post['id'] ?>" type="hidden" />
        <!-- /product_profile_details -->
      </form>
    </div>
    <!-- /product-profile -->
    <?php //var_dump($active_categories); 
      $related = array();
	  $related['selected_categories'] = array($active_categories[1]);
	  $limit[0] = 0;
	  $limit[1] = 40;
	  $related = CI::model ( 'content' )->getContentAndCache($related, false,$limit ); 
	  shuffle( $related );
	  $related = array_slice($related,0,4);
	  ?>
    <?php if(!empty($related)): ?>
    <!-- related -->
    <div class="related wrap">
      <h2>Related Products</h2>
      <?php foreach($related as $item): ?>
      <?php $this_shp_item_price = CI::model ( 'cart' )->currencyConvertPrice($item['custom_fields']['price'], $this->session->userdata ( 'shop_currency' )); ?>
      <div class="item_wrap">
        <div class="box boxv2" style="background-image:url('<?php print   $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId($item['id'], 280); ?>'); background-position:center center; background-repeat:no-repeat;"> <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($item['id']) ; ?>"></a> </div>
        <em class="related-item-name"><?php print character_limiter($item['content_title'], 10, '...'); ?></em> <em class="related-item-price"><strong><?php print $this->session->userdata ( 'shop_currency_sign' ) ?> <?php print ($this_shp_item_price); ?></strong></em> </div>
      <?php endforeach; ?>
    </div>
    <!-- /related -->
    <?php endif;  ?>
  </div>
</div>
<!-- /main -->
