<?php

/*

type: layout

name: sidebar

description: sidebar

*/
?>
<?



$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>
<div class="module-posts-template-sidebar">
  <? if (!empty($data)): ?>
  <ul>
  <?

   $count = -1;
    foreach ($data as $item):

    $count++;


   ?>



            <li class="media">
              <a href="<? print $item['link'] ?>" class="pull-left">
              <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <img src="<? print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" class="img-rounded img-polaroid"  />
              <? endif; ?>
              </a>
              <div class="media-body extra-wrap">
              <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
               <strong><a href="<? print $item['link'] ?>" class="media-heading"><? print $item['title'] ?></a></strong>
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
			  if( $add_cart_text == false){  $add_cart_text =  'Add to cart';  }

        ?>

				 <? if(isarr( $item['prices'])): ?>
                <button class="btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<? print $item['id'].$count ?>');"><i class="icon-shopping-cart"></i>&nbsp;<? print $add_cart_text ?></button>
                <? endif; ?>
        <? endif; ?>
      </div>
      <? if(isarr( $item['prices']) ): ?>
      <? foreach( $item['prices']  as $k  => $v): ?>

      <div class="clear products-list-proceholder mw-add-to-cart-<? print $item['id'].$count ?>">
        <input type="hidden"  name="price" value="<? print $v ?>" />
        <input type="hidden"  name="content_id" value="<? print $item['id'] ?>" />
      </div>
      <?php break; endforeach ; ?>
      <?php endif; ?>


                <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="date"><? print $item['created_on'] ?></small>
                <?php endif; ?>

               <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
               <p><? print $item['description'] ?></p>
               <? endif; ?>




                <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                    <a href="<? print $item['link'] ?>" class="btn btn-mini"><? $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?></a>
                <?php  endif; ?>
               </div>
            </li>
  <? endforeach; ?>
   </ul>
  <? endif; ?>
</div>
<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}") ?>

 <? endif; ?>








