<?php

/*

type: layout

name: Shopmag

description: Shopmag

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
<div class="module-products-template-shopmag" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>


<script>mw.require("<?php print $config['url_to_module']; ?>js/masonry.pkgd.min.js", true); </script>
<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>
<script>
    mw._masons = mw._masons || [];
    $(document).ready(function(){
        var m = mw.$('#posts-<?php print $rand; ?>');
        m.masonry({
          "itemSelector": '.masonry-item',
          "gutter":0
        });
        mw._masons.push(m);
        if(typeof mw._masons_binded === 'undefined'){
            mw._masons_binded = true;
               setInterval(function(){
                 var l = mw._masons.length, i=0;
                 for( ; i<l; i++){
                   var _m = mw._masons[i];
                   if(mw.$(".masonry-item", _m[0]).length > 0){
                       _m.masonry({
                          "itemSelector": '.masonry-item',
                          "gutter":0
                       });
                   }
                 }
               }, 500);
        }

    });
</script>


    <?php


    $count = -1;
    foreach ($data as $item):

    $count++;

    ?>



    <div class="masonry-item">
    <div class="masonry-item-content">

      <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>

<?php  $second_picture = false; ?>
      <a href="<?php print $item['link'] ?>" class="bgimage-fader">
         <?php $second_pictures = get_pictures($item['id']); ?>
<?php if(isset($second_pictures[1]) and isset($second_pictures[1]['filename'])): ?>
<?php $second_picture = $second_pictures[1]['filename']; ?>
<?php endif; ?>
   

      <span style="background-image: url(<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>);"></span>
      <?php if($second_picture): ?>
      <span style="background-image: url(<?php print thumbnail($second_picture, $tn[0], $tn[1]); ?>);"></span>
<?php endif; ?>




      </a>
      <?php endif; ?>
      <div class="masonry-item-container">
      <?php if($show_fields == false or in_array('title', $show_fields)): ?>
      <h3><a  class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
      <?php endif; ?>
      <?php if($show_fields != false and in_array('created_on', $show_fields)): ?>
        <?php /*<span class="date"><?php print $item['created_on'] ?></span>*/ ?>
      <?php endif; ?>
      <?php if($show_fields == false or ($show_fields != false and  is_array($show_fields) and  in_array('description', $show_fields))): ?>
      <?php /*<p class="description"> <?php print $item['description']; ?> </p>*/ ?>
      <?php endif; ?>





      <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>

        <?php /*<a href="<?php print $item['link'] ?>" class="mw-more"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>*/ ?>

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


                <?php /*<button class="mw-ui-btn mw-ui-btn-invert" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');">&nbsp;<?php print $add_cart_text ?></button>*/ ?>

                    <span class="sm-icon-bag btnaddtocart tip pull-right" data-tipposition="left-center" data-tip="<?php print $add_cart_text ?>" onclick='AddToCart(".mw-add-to-cart-<?php print $item['id'].$count ?>","<?php print $val1; ?>", "<?php print  $item['title'] ; ?>");'></span>



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
    </div>
    <?php  endforeach; ?>

  <?php else: ?>

    <h2 style="padding: 20px;text-align: center">No products found</h2>

  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&class=mw-paging") ?>
<?php endif; ?>
