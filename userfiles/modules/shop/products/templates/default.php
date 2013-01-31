<?php

/*

type: layout

name: Products

description: Products

*/
?>

<div  class="products-holder">
  <? if (!empty($data)): ?>
  <ul class="thumbnails thumbnails_3">
    <? foreach ($data as $item): ?>
    <li class="span2">
      <div class="thumbnail_3">
        <? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <a href="<? print $item['link'] ?>">
        <figure class="img-circle"><img src="<? print thumbnail($item['image'], 159); ?>" alt="" class="img-circle"></figure>
        </a>
        <? endif; ?>
        <div class="team-item-info">
          <? if($show_fields == false or in_array('title', $show_fields)): ?>
          <a href="<? print $item['link'] ?>" class="lead"><? print $item['title'] ?></a><br>
          <? endif; ?>
          <? if($show_fields == false or in_array('description', $show_fields)): ?>
          <? print $item['description'] ?>
          <? endif; ?>
          <? if(isarr( $item['prices'])): ?>
          <? $i=1 ;foreach($item['prices']  as $k  => $v): ?>
          <div class="mw-price-holder mw-add-to-cart-<? print $item['id'].$i ?>">
            <input type="hidden"  name="price" value="<? print $v ?>" />
            <input type="hidden"  name="content_id" value="<? print $item['id'] ?>" />
            <? if($show_fields == false or in_array('price', $show_fields)): ?>
            <span class="mw-price"><? print $k ?>: <? print $v ?></span>
            <? endif; ?>
            <? if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
            <?
			   
			  $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){
				     $add_cart_text =  'Add to cart';
			  }
			  ?>
            <button class="btn btn-primary" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $item['id'].$i ?>');"><? print $add_cart_text ?></button>
            <? endif; ?>
            <? 
			break;
			$i++; endforeach ; ?>
            <? endif; ?>
          </div>
        </div>
      </div>
    </li>
    <? endforeach; ?>
  </ul>
  <? endif; ?>
</div>
<? if (!empty($paging_links)): ?>
<div class="pagination indent-1 pagination-right">
  <ul>
    <? foreach ($paging_links as $k=>$item): ?>
    <li><a href="<? print $item ?>"><? print $k ?></a></li>
    <? endforeach; ?>
  </ul>
</div>
<? endif; ?>
