<?php

/*

type: layout

name: 3 Columns

description: 3 Columns

*/
?>



<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 250;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<div class="clearfix container-fluid module-posts-template-columns module-posts-template-columns-3">
  <? if (!empty($data)): ?>
  <div class="row-fluid">
    <?


        $count = -1;
    foreach ($data as $item):

     $count++;

    ?>


    <?php if($count % 3 == 0) { ?><div class="v-space"></div><?php } ?>
    <div class="span4<?php if($count % 3 == 0) { ?> first <?php } ?>">
      <? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a class="img-polaroid img-rounded" href="<? print $item['link'] ?>">
        <span class="valign">
            <span class="valign-cell">
                <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<? print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="<? print $item['title'] ?>" title="<? print $item['title'] ?>"  />
            </span>
        </span>
      </a>
      <? endif; ?>
      <? if($show_fields == false or in_array('title', $show_fields)): ?>
      <h3><a  class="lead" href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h3>
      <? endif; ?>
      <? if($show_fields != false and ($show_fields != false and  in_array('description', $show_fields))): ?>
      <p class="description">
        <? print $item['description']; ?>
      </p>
      <? endif; ?>
      <div class="product-price-holder clearfix">
        <? if($show_fields == false or in_array('price', $show_fields)): ?>
        <?php if(isset($item['prices']) and isarr($item['prices'])){  ?>
        <span class="price"><? print currency_format(array_shift(array_values($item['prices']))); ?></span>
        <?php } else{ ?>

        <?php } ?>
        <? endif; ?>
        <? if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
        <?

			  $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){
				     $add_cart_text =  'Add to cart';
			  }
			  ?>
				 <? if(isarr( $item['prices'])): ?>
                <button class="btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $item['id'].$i ?>');"><i class="icon-shopping-cart"></i>&nbsp;<? print $add_cart_text ?></button>
                <? endif; ?>
        <? endif; ?>
      </div>
      <? if(isarr( $item['prices'])): ?>
      <? foreach($item['prices']  as $k  => $v): ?>

      <div class="clear products-list-proceholder mw-add-to-cart-<? print $item['id'].$i ?>">
        <input type="hidden"  name="price" value="<? print $v ?>" />
        <input type="hidden"  name="content_id" value="<? print $item['id'] ?>" />
      </div>
      <?
			break;
			 endforeach ; ?>
      <?  endif; ?>
    </div>

    <?  endforeach; ?>
  </div>
  <? endif; ?>
</div>
<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<? print paging("num={$pages_count}&paging_param={$paging_param}") ?>
<? endif; ?>
