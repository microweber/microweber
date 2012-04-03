<?php include(ACTIVE_TEMPLATE_DIR.'shop_side_nav.php') ;  ?>
<?php if(!empty($posts)): ?>

<div id="main">
  <? if((count($active_categories) == 1) and $page['id'] == 194  ) :  ?>
  <? $collections = CI::model ( 'taxonomy' )->getChildrensRecursive(2060, 'category');  
  //p($collections);
   @array_shift($collections);
  
   //$cat_pic = $this->core_model->mediaGetThumbnailForItem('table_taxonomy', $active_categories[1], $size = 'original', $order_direction = "DESC", $media_type = 'picture', $do_not_return_default_image = true); 
  ?>
  <? if(!empty($collections)): ?>
  <div id="cat_info">
    <?php foreach ($collections as $coll): 
	$coll1 = CI::model ( 'taxonomy' )->getSingleItem($coll);
	$cat_pic = $this->core_model->mediaGetThumbnailForItem('table_taxonomy', $coll, $size = 350, $order_direction = "DESC", $media_type = 'picture', $do_not_return_default_image = true); 
	?>
    <div class="cat_info_block">
      <?php if($cat_pic): ?>
      <a href="<? print CI::model ( 'taxonomy' )->getUrlForIdAndCache($coll) ?>" class="cat_img box boxv2" style="background-image: url('<? print $cat_pic ?>')"> </a>
      <?php endif; ?>
      <div class="cattxt">
        <h3><a href="#"><? print $coll1['taxonomy_value'] ?></a></h3>
        <? print html_entity_decode(character_limiter($coll1['content_body'], 650)) ?> <a class="small_btn right" href="<? print CI::model ( 'taxonomy' )->getUrlForIdAndCache($coll) ?>"><span>View products</span></a> </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <!-- /#cat_info -->
  <?php else: ?>
  <? if((count($active_categories) > 1) and $page['id'] == 194  ) :
  $coll1 = CI::model ( 'taxonomy' )->getSingleItem($active_categories[1]);
  $cat_pic = $this->core_model->mediaGetThumbnailForItem('table_taxonomy', $active_categories[1], $size = 780, $order_direction = "DESC", $media_type = 'picture', $do_not_return_default_image = true);

  ?>
  <?php 
$cat_pic_dir = ROOTPATH.'/category_backgrounds/'.$active_categories[1].'/'; 
  
$gall = (glob($cat_pic_dir."*.jpg"));

?>
  <? if(!empty($gall)): ?>
  <div  id="OMfader">
    <? foreach($gall as $pic1): ?>
    <a href="#" class="randomize_image cat_img box boxv2" style="background-image: url('<?php print pathToURL($pic1); ?>')"> </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <div class="cat_info_block_inner">
    <? if($cat_pic): ?>
    <!--    <a href="<? print CI::model ( 'taxonomy' )->getUrlForIdAndCache($active_categories[1]) ?>" class="kailXY cat_img box boxv2" style="width:777px;height:400px;background: url('<? print $cat_pic ?>') no-repeat center"> </a>-->
    <? endif; ?>
    <script type="text/javascript">




    $(document).ready(function(){
        var hash = window.location.hash;
        if(hash=='#fade'){
         //   $("#OMfader").show();
         //   $(".kailXY").hide();

        }//endif;

 fader = {}
var fade_speed = 700;

fader.func = function(selector, time){
    selector.hover(function(){
      $(this).addClass("randomize_image_hover");
    }, function(){
      $(this).removeClass("randomize_image_hover");
    });

    setInterval(function(){
      var visible = selector.find(".randomize_image:visible");
      var isLast = visible.next(".randomize_image");
      if(!selector.hasClass("randomize_image_hover")){
          if(isLast.length>0){
             visible.fadeOut(fade_speed);
             visible.next(".randomize_image").fadeIn(fade_speed);
          }
          else{
            visible.fadeOut(fade_speed);
            selector.find(".randomize_image:first").fadeIn(fade_speed);
          }
      }

    }, time);
}
fader.init = function(selector, time){
    var elem = $(selector);
    elem.find(".randomize_image:first").show();
    fader.func(elem, time);
}

fader.init("#OMfader", 3000);

    });

    </script>
    <div class="c">&nbsp;</div>
    <div class="cattxt" style="float: none;width: auto">
      <h3><a href="#"><? print $coll1['taxonomy_value'] ?></a></h3>
      <? print html_entity_decode($coll1['content_body']) ?> </div>
  </div>
  <div class="c">&nbsp;</div>
  <?php endif; ?>
  <div class="store-items">
    <?php foreach ($posts as $the_post): ?>
    <?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $the_post['id']);
	$the_post['custom_fields'] = $more;
	?>
    <?php $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId($the_post['id'], 300); ?>
    <?php if($item['content_description'] != ''): ?>
    <?php $some_item_desc =  addslashes(character_limiter($the_post['content_title'], 250, '...')); ?>
    <?php else: ?>
    <?php $some_item_desc =  addslashes ( character_limiter($the_post['content_title'], 250, '...')); ?>
    <?php endif; ?>
    <div class="item_wrap" title="<?php print $some_item_desc; ?>">
      <div class="box boxv2" style="background-image:url('<?php print $thumb ?>')"> <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>"></a> </div>
      <em class="related-item-name"><?php print character_limiter($the_post['content_title'], 10, '...'); ?></em>
      <?php $this_shop_item_price = CI::model ( 'cart' )->currencyConvertPrice($the_post['custom_fields']['price'], $this->session->userdata ( 'shop_currency' )); ?>
      <!--<em class="related-item-price"><strong>&euro; <?php print $the_post['custom_fields']['price']; ?></strong>-->
      <em class="related-item-price <?php if(intval($the_post['custom_fields']['old_price']) != 0) : ?>hasOldPrice<?php endif; ?>"><strong><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><?php print ($this_shop_item_price); ?></strong> </em>
      <?php if(intval($the_post['custom_fields']['old_price']) != 0) : ?>
      <s class="old-price"><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><?php print  ceil(CI::model ( 'cart' )->currencyConvertPrice($the_post['custom_fields']['old_price'], $this->session->userdata ( 'shop_currency' )));   ?></s>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<script type="text/javascript">
      $(function(){
        $(".item_wrap:nth-child(4n)").css("marginRight", "0px");
      });
</script>
<div style="height:12px"></div>
<?php if(!empty($posts_pages_links)): ?>
<?php print $page_link ;  ?>
<? if((count($active_categories) == 1) and $page['id'] == 194  ) :  ?>
<?php else : ?>
<? paging('uls') ?>
<?php endif ; ?>
<?php endif ; ?>
<?php else : ?>
<div class="txt news wrap">
  <p> <strong>Coming soon!</strong> </p>
</div>
<?php endif; ?>
<div class="clear" style="height:25px">
  <!--  -->
</div>
