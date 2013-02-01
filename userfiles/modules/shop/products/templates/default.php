<?php

/*

type: layout

name: Products

description: Products

*/
?>

<div class="mw-products-list products-list-default">
  <? if (!empty($data)): ?>
  <div class="row-fluid">
    <?  $j=1;  foreach ($data as $item): ?>
    <div class="span4">
      <? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a class="img-polaroid products-list-image" href="<? print $item['link'] ?>">
        <span><img src="<? print thumbnail($item['image'], 190,190); ?>" alt="><? print $item['title'] ?>" title="><? print $item['title'] ?>"  /></span>
      </a>
      <? endif; ?>

        <? if($show_fields == false or in_array('title', $show_fields)): ?>
        <a href="<? print $item['link'] ?>" class="lead"><? print $item['title'] ?></a>
        <? endif; ?>

        <? if($show_fields == false or in_array('description', $show_fields)): ?>
          <div class="mw-products-list-item-info">
            <?  print $item['description'] ?>
          </div>
        <? endif; ?>

        <? if(isarr( $item['prices'])): ?>
        <? $i=1 ;foreach($item['prices']  as $k  => $v): ?>
        <div class="clear products-list-proceholder mw-add-to-cart-<? print $item['id'].$i ?>">
          <input type="hidden"  name="price" value="<? print $v ?>" />
          <input type="hidden"  name="content_id" value="<? print $item['id'] ?>" />

          <? if($show_fields == false or in_array('price', $show_fields)): ?>

          <span class="products-list-price">$<? print $v ?></span>
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
        </div>
        <?  endif; ?>

    </div>
    <?  if($j % 3 == 0):  ?>
  </div>
  <div class="row-fluid">
    <?  endif; ?>
    <?  $j++; endforeach; ?>
  </div>
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
