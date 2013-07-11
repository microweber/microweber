<?php

/*

type: layout

name: sidebar

description: sidebar

*/
?>
<?php



$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 70;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}
?>
<div class="module-posts-template-sidebar">
  <?php if (!empty($data)): ?>
  <ul>
  <?php

   $count = -1;
    foreach ($data as $item):

    $count++;


   ?>



            <li class="media">
              <a href="<?php print $item['link'] ?>" class="pull-left">
              <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" class="img-rounded img-polaroid"  />
              <?php endif; ?>
              </a>
              <div class="media-body extra-wrap">
              <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
               <strong><a href="<?php print $item['link'] ?>" class="media-heading"><?php print $item['title'] ?></a></strong>
               <?php endif; ?>


                     <div class="product-price-holder clearfix">
        <?php if($show_fields == false or in_array('price', $show_fields)): ?>
        <?php if(isset($item['prices']) and isarr($item['prices'])){  ?>
        <span class="price"><?php print currency_format(array_shift(array_values($item['prices']))); ?></span>
        <?php } else{ ?>

        <?php } ?>
        <?php endif; ?>
        <?php if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
        <?php
                $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){  $add_cart_text =  'Add to cart';  }

        ?>

				 <?php if(isarr( $item['prices'])): ?>
                <button class="btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');"><i class="icon-shopping-cart"></i>&nbsp;<?php print $add_cart_text ?></button>
                <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php if(isarr( $item['prices']) ): ?>
      <?php foreach( $item['prices']  as $k  => $v): ?>

      <div class="clear products-list-proceholder mw-add-to-cart-<?php print $item['id'].$count ?>">
        <input type="hidden"  name="price" value="<?php print $v ?>" />
        <input type="hidden"  name="content_id" value="<?php print $item['id'] ?>" />
      </div>
      <?php break; endforeach ; ?>
      <?php endif; ?>


                <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="date"><?php print $item['created_on'] ?></small>
                <?php endif; ?>

               <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
               <p><?php print $item['description'] ?></p>
               <?php endif; ?>




                <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                    <a href="<?php print $item['link'] ?>" class="btn btn-mini"><?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?></a>
                <?php  endif; ?>
               </div>
            </li>
  <?php endforeach; ?>
   </ul>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}") ?>

 <?php endif; ?>








