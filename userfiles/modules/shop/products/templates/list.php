<?php

/*

type: layout

name: List

description: List

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


<div class="post-list">
  <?php if (!empty($data)): ?>
  <?php

  $count = -1;
    foreach ($data as $item):

    $count++;


   ?>
  <div class="well clearfix post-single">
      <div class="row">
          <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <div class="col-sm-4">
                <a href="<?php print $item['link'] ?>"><img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" class="img-rounded img-polaroid" alt="" ></a>
            </div>
          <?php endif; ?>
          <div class="col-sm-8">
              <div class="post-single-title-date" style="padding-bottom: 0;">
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <h2 class="lead"><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h2>
                  <?php endif; ?>
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="muted">Date: <?php print $item['created_on'] ?></small>
                  <?php endif; ?>
              </div>


                    <div class="product-price-holder clearfix">
        <?php if($show_fields == false or in_array('price', $show_fields)): ?>
        <?php if(isset($item['prices']) and is_array($item['prices'])){  ?>
 <?php 
		$vals2 = array_values($item['prices']);
		$val1 = array_shift($vals2); ?>
        <span class="price"><?php print currency_format($val1); ?></span>        <?php } else{ ?>

        <?php } ?>
        <?php endif; ?>
        <?php if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
        <?php
                $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false){
			        $add_cart_text =  _e("Add to cart", true);
              }

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



              <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                <p class="description"><?php print $item['description'] ?></p>
              <?php endif; ?>

              <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                  <a href="<?php print $item['link'] ?>" class="btn btn-default">
                      <?php $read_more_text ? print $read_more_text : print _e("Continue Reading", true); ?>
                  </a>
              <?php endif; ?>
          </div>
      </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}") ?>
<?php endif; ?>
