
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
        <td><microweber module="content/custom_fields" no_wrap="true" content_id="<? print $item['id'] ?>" only_type="price" only_value="1"  module_id="custom_field_price_product<? print $item['id'] ?>" /></td>
        <td>&nbsp;<?php print option_get('shop_currency_sign') ; ?></td>
      </tr>
    </table>
  </li>
  <? endforeach; ?>
</ul>
