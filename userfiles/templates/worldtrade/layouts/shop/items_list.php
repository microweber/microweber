
<ul class="products_list slide_box">
  <? foreach($posts_data  as $item) :?>
  <li>
    <div class="rounded_box roundex_box_size transparent left"     >
      <? $media =   get_media($item['id']);  ; ?>
      <? if(!empty($media["pictures"])): ?>
      <a href="<? print post_link($item['id']);?>"> <img   border="0"    src="<? print get_media_thumbnail( $media["pictures"][0]['id'] , 177)  ?>" width="177"  alt="<? print addslashes($item['content_title']);?>" /> </a>
      <? endif; ?>
      <div class="lt"></div>
      <div class="rt"></div>
      <div class="lb"></div>
      <div class="rb"></div>
    </div>
    <div class="clener"></div>
    <h3 class="pink_color"> <a href="<? print post_link($item['id']);?>"><? print ($item['content_title']);?></a></h3>
    <p><? print character_limiter($item['content_description'], 150);?></p>
    <table border="0" class="full_price_total" cellspacing="0" cellpadding="0"  onclick="location.href='<? print post_link($item['id']);?>'" style="cursor:pointer">
      <tr>
        <td>Цена:</td>
        <td>     
      <? 

	$p = cf_val($item['id'], 'price');
	
	
	
 
	//$p  = explode('_', $p );
	$price = false;
if(!empty($p )){
	$p=array_merge(array(),$p);
	 
	if(user_id() != false){
		 $price = $p[1]['value'];
	} else {
		 $price= $p[0]['value'];
	}
	}
	 
 print $price;
	?></td>
        <td>&nbsp;<?php print option_get('shop_currency_sign') ; ?></td>
      </tr>
    </table>
  </li>
  <? endforeach; ?>
</ul>
