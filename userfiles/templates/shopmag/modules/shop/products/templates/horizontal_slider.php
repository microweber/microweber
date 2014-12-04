<?php

/*

type: layout

name: Post Bottom

description: Can be used as: Horizontal slider in the Bottom part of the post

*/
?>



<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 400;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<?php  $rand = uniqid(); ?>
<div class="module-products-template-slider" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>



    <?php


    $count = -1;
    foreach ($data as $item):

    $count++;

    ?>


    <?php $hasDescription = ($show_fields == false or ($show_fields != false and  is_array($show_fields) and  in_array('description', $show_fields))); ?>

    <div class="module-products-template-slider-item <?php if($hasDescription){print 'tip';} ?>"  <?php if($hasDescription){ print 'data-tipposition="top-center" data-tip="#description-tip-'.  $item['id']  .'"'; }?>>

    <?php if($hasDescription){ ?>

        <div id="description-tip-<?php print $item['id']; ?>" class="description-tip" style="display: none;"><div class="description-tip-content"><?php print $item['description']; ?></div></div>

    <?php } ?>



    <div class="module-products-template-slider-item-content">

      <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>


      <a href="<?php print $item['link'] ?>" class="bgimage-fader">

      <?php $second_picture = get_pictures($item['id'])[1]['filename']; ?>

      <span style="background-image: url(<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>);"></span>
      <span style="background-image: url(<?php print thumbnail($second_picture, $tn[0], $tn[1]); ?>);"></span>



      </a>
      <?php endif; ?>
      <div class="module-products-template-slider-item-container">
      <?php if($show_fields == false or in_array('title', $show_fields)): ?>
      <h3><a  class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
      <?php endif; ?>
      <?php if($show_fields != false and in_array('created_at', $show_fields)): ?>
      <span class="date"><?php print $item['created_at'] ?></span>
      <?php endif; ?>









      <div class="module-products-template-slider-item-price-holder">
        <?php if($show_fields == false or in_array('price', $show_fields)): ?>
        <?php if(isset($item['prices']) and is_array($item['prices'])){  ?>
	    <?php 
		$vals2 = array_values($item['prices']);
		$val1 = array_shift($vals2); ?>
        <span class="price"><?php print currency_format($val1); ?></span>

         <?php } else{ ?>

        <?php } ?>
        <?php endif; ?>
        <?php if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
        <?php
                $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){  $add_cart_text =  _e("Add to cart", true); }

        ?>

				 <?php if(is_array( $item['prices'])): ?>

                <span class="sm-icon-bag btnaddtocart tip" data-tip="<?php _e("Add to cart"); ?>" onclick="AddToCart('.mw-add-to-cart-<?php print $item['id'].$count ?>','<?php print $val1; ?>', '<?php print $item['title']; ?>');"></span>

                <?php endif; ?>
        <?php endif; ?>
      </div>

      <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>

        <a href="<?php print $item['link'] ?>" class="read-more mw-ui-link"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

      <?php endif; ?>
      <?php if(is_array( $item['prices']) ): ?>
      <?php foreach( $item['prices']  as $k  => $v): ?>

      <div class="clear products-list-proceholder mw-add-to-cart-<?php print $item['id'].$count ?>">
        <input type="hidden"  name="price" value="<?php print $v ?>" />
        <input type="hidden"  name="content_id" value="<?php print $item['id'] ?>" />
      </div>
      <?php break; endforeach ; ?>
      <?php endif; ?>
    </div>
    </div>
    </div>
    <?php  endforeach; ?>

  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&class=mw-paging") ?>
<?php endif; ?>
