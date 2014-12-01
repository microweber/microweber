<?php

/*

type: layout

name: Single Item

description: Single Item

*/
?>



<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 650;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<?php  $rand = uniqid(); ?>
<div class="clearfix module-posts-template-mwpgrid module-posts-template-mwpgrid-single module-posts-template-mwpgrid-liteness" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>


<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>



    <?php


    $count = -1;
    foreach ($data as $item):

    $count++;

    ?>



    <div class="mwpgrid-item">

      <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>" class="mwpgrid-item-rock-image" style="background-image: url();"><img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="" /></a>
      <?php endif; ?>
      <div class="mwpgrid-item-container">
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

        <a href="<?php print $item['link'] ?>" class="mw-more"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

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
                <span class="tip mw-icon-shop " data-tip="<?php print $add_cart_text ?>" data-tipposition="top-center"  onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');"></span>
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
    </div>
    <?php  endforeach; ?>

  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}") ?>
<?php endif; ?>
