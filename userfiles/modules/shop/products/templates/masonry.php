<?php

/*

type: layout

name: Masonry

description: Masonry

*/
?>



<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 300;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<?php  $rand = uniqid(); ?>
<div class="clearfix module-posts-template-masonry" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>


<script>mw.require("<?php print $config['url_to_module']; ?>js/masonry.pkgd.min.js", true); </script>
<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>
<script>
    mw._masons = mw._masons || [];
    $(document).ready(function(){
        var m = mw.$('#posts-<?php print $rand; ?>');
        m.masonry({
          "itemSelector": '.masonry-item',
          "gutter":5
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
                          "gutter":5
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

      <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>">

                <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>"  />

      </a>
      <?php endif; ?>
      <div class="masonry-item-container">
      <?php if($show_fields == false or in_array('title', $show_fields)): ?>
      <h3><a  class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
      <?php endif; ?>
      <?php if($show_fields != false and in_array('created_at', $show_fields)): ?>
      <span class="date"><?php print $item['created_at'] ?></span>
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
                <button class="mw-ui-btn" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');"><i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;<?php print $add_cart_text ?></button>
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
