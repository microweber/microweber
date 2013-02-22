<?php

/*

type: layout

name: Columns

description: Columns

*/
?>

<div class="clearfix container-fluid module-posts-template-columns">
  <? if (!empty($data)): ?>
  <div class="row-fluid">
    <?  $j=1;  foreach ($data as $item): ?>
    <div class="span4">
      <? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<? print $item['link'] ?>">
        <img class="img-polaroid img-rounded" src="<? print thumbnail($item['image'], 290); ?>" alt="<? print $item['title'] ?>" title="<? print $item['title'] ?>"  />
      </a>
      <? endif; ?>
      <div class="product-title-price clearfix">
        <? if($show_fields == false or in_array('title', $show_fields)): ?>
        <h3 class="pull-left"><a  class="lead" href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h3>
        <? endif; ?>
        <? if($show_fields == false or in_array('price', $show_fields)): ?>

        <?php if(isset($item['prices']) and isarr($item['prices'])){  ?>
            <span class="price"><? print currency_format(array_shift(array_values($item['prices']))); ?></span>
         <?php } else{ ?>
            <span class="price"><? print currency_format($v); ?></span>
         <?php } ?>
        <? endif; ?>
     </div>
        <? if($show_fields == false or in_array('description', $show_fields)): ?>
          <p class="description">
            <?  print $item['description'] ?>
          </p>
        <? endif; ?>

        <? if(isarr( $item['prices'])): ?>
        <? $i=1 ;foreach($item['prices']  as $k  => $v): ?>
        <div class="clear products-list-proceholder mw-add-to-cart-<? print $item['id'].$i ?>">
          <input type="hidden"  name="price" value="<? print $v ?>" />
          <input type="hidden"  name="content_id" value="<? print $item['id'] ?>" />


          <? if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
          <?

			  $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){
				     $add_cart_text =  'Add to cart';
			  }
			  ?>
          <button class="btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $item['id'].$i ?>');"><i class="icon-shopping-cart"></i>&nbsp;<? print $add_cart_text ?></button>
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
