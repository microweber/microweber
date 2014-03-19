<?php

/*

type: layout

name: 4 Columns

description: 4 Columns

*/
?>



<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 350;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<div class="clearfix container-fluid module-posts-template-columns module-posts-template-columns-4">
  <?php if (!empty($data)): ?>
  <div class="row row-fluid">
    <?php


    $count = -1;
    foreach ($data as $item):

    $count++;

    ?>


    <?php if($count % 4 == 0) { ?><div class="v-space"></div><?php } ?>
    <div class="col-sm-3<?php if($count % 4 == 0) { ?> first <?php } ?>">
      <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a class="img-polaroid img-rounded" href="<?php print $item['link'] ?>">
        <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>"  />
      </a>
      <?php endif; ?>
      <?php if($show_fields == false or in_array('title', $show_fields)): ?>
      <h3><a  class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
      <?php endif; ?>
      <?php if($show_fields != false and in_array('created_on', $show_fields)): ?>
      <span class="date"><?php print $item['created_on'] ?></span>
      <?php endif; ?>
      <?php if($show_fields == false or ($show_fields != false and  is_array($show_fields) and  in_array('description', $show_fields))): ?>
      <p class="description"> <?php print $item['description']; ?> </p>
      <?php endif; ?>





      <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>

        <a href="<?php print $item['link'] ?>"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

      <?php endif; ?>


      <div class="product-price-holder clearfix">
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
                <button class="btn btn-default" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');"><i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;<?php print $add_cart_text ?></button>
                <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php if(is_array( $item['prices']) ): ?>
      <?php foreach( $item['prices']  as $k  => $v): ?>

      <div class="clear products-list-proceholder mw-add-to-cart-<?php print $item['id'].$count ?>">
        <input type="hidden"  name="price" value="<?php print $v ?>" />
        <input type="hidden"  name="content_id" value="<?php print $item['id'] ?>" />
      </div>
      <?php break; endforeach ; ?>
      <?php endif; ?>
    </div>
    <?php  endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}") ?>
<?php endif; ?>
