<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 



 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
	$products  = array();			 
if(intval($shop_page['content_subtype_value']) != 0){
$products ['selected_categories'] = array($shop_page['content_subtype_value']);
}

$products ['items_per_page'] = 20;


$products = get_posts($products);
 
	 
	 ?>

<div id="middle">
  <div class="right_col right">
    <div class="rounded_box transparent home_slide">
      <div id="related_products">
        <? 
	  
	  $i = 0;
	  
	  foreach($products["posts"] as $product): ?>
        <? if($i < 5): ?>
        <? $media =   get_media($product['id'], $for = 'post');  ; ?>
        <? // p($media ); ?>
        <? if(!empty($media["pictures"])): ?>
        <a href="<? print post_link($product['id']);?>"><img src="<? print $media["pictures"][0] ["urls"]['original'] ?>" alt="<? print addslashes($product['content_title']);?>" border="0" width="620" /></a>
        <? unset($products["posts"][$i]); ?>
        <? endif; ?>
        <? endif; ?>
        <? $i++; ?>
        <? endforeach ; ?>
        <!--  <img src="<? print TEMPLATE_URL ?>images/other/home-slide/img2.jpg" alt="" />
      
      
       <img src="<? print TEMPLATE_URL ?>images/other/home-slide/img3.jpg" alt="" />-->
      </div>
      <div class="lt"></div>
      <div class="rt"></div>
      <div class="lb"></div>
      <div class="rb"></div>
      <div id="pager"></div>
      <div class="slide-btn_box" id="next"></div>
      <div class="slide-btn_box" id="prev"></div>
    </div>
    <div class="clener"></div>
    <div class="rounded_box small_preview">
      <ul class="content">
        <?   $i = 0;  foreach($products["posts"] as $product): ?>
        <? if($i < 6): ?>
        <? $media =   get_media($product['id'], $for = 'post');  ; ?>
        <? if(!empty($media["pictures"])): ?>
        <li class="rounded_box left"><a href="<? print post_link($product['id']);?>"> <img width="90" border="0" src="<? print get_media_thumbnail( $media["pictures"][0]['id'] , 120)  ?>" alt="<? print addslashes($product['content_title']);?>" /></a> </li>
        <? unset($products["posts"][$i]); ?>
        <? endif; ?>
        <? endif; ?>
        <? $i++; ?>
        <? endforeach ; ?>
        <!--   <li class="rounded_box left"> <img src="<? print TEMPLATE_URL ?>images/other/small_preview/small_preview_05.jpg" alt="" /> </li>
        <li class="rounded_box left"> <img src="<? print TEMPLATE_URL ?>images/other/small_preview/small_preview_07.jpg" alt="" /> </li>
        <li class="rounded_box left"> <img src="<? print TEMPLATE_URL ?>images/other/small_preview/small_preview_09.jpg" alt="" /> </li>-->
      </ul>
      <div class="lt"></div>
      <div class="rt"></div>
      <div class="lb"></div>
      <div class="rb"></div>
      <div class="clener h10"></div> 
    </div>
    <img src="<? print TEMPLATE_URL ?>images/img1.jpg" alt="" />
    <h1 class="pink_color font_size_18 home_products_list_title">Нови модели</h1>
    <ul class="home_products_list slide_box">
     
     
     
      <?   $i = 0;  foreach($products["posts"] as $product): ?>
      <? if($i < 8): ?>
      <? $media =   get_media($product['id'], $for = 'post');  ; ?> 
     
      <li>
        <div class="left"><a href="<? print post_link($product['id']);?>"><img src="<? print get_media_thumbnail( $media["pictures"][0]['id'] , 150)  ?>" border="0" height="200" width="136" alt="<? print addslashes($product['content_title']);?>" /></a> </div>
        <div class="clener"></div>
        <h3><? print $product['content_title'] ?></h3>
        
      
        
        <p><? print character_limiter($product['content_description'], 100); ?>
        
        
         <table border="0" class="full_price_total" cellspacing="0" cellpadding="0"  onclick="location.href='<? print post_link($item['id']);?>'" style="cursor:pointer">
      <tr>
        <td>Цена:</td>
        <td><microweber module="content/custom_fields" no_wrap="true" content_id="<? print $product['id'] ?>" only_type="price" only_value="1"  module_id="custom_field_price_product<? print $product['id'] ?>" /></td>
        <td>&nbsp;<?php print option_get('shop_currency_sign') ; ?></td>
      </tr>
    </table>
        </p>
         
      </li>
      
      <? endif; ?>
      
      <? $i++; ?>
      <? endforeach ; ?>
      
      
      
      
  
      </li>
    </ul>
  </div>
  <? include   TEMPLATE_DIR.  "sidebar.php"; ?>
  <div class="clener"></div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
