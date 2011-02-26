<?php

/*

type: layout

name: shop layout

description: shop site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? include TEMPLATE_DIR."sidebar.php"; ?>

<div id="main">
  <? if(empty($post)): ?>
  <? foreach($posts as $post): ?>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>
    <editable  post="<? print $post['id'] ?>" field="content_title"><? print $post['content_title'] ?></editable>
    </strong> <span class="best_seller">&nbsp;</span> </a> <a href="<? print post_link($post['id']) ?>" class="ai">
    <editable  post="<? print $post['id'] ?>" field="custom_field_content_description_small">Enter content description</editable>
    </a> </div>
  <? endforeach; ?>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> </a> <a href="#" class="ai">See all products from this category</a> </div>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a> <a href="#" class="ai">See all products from this category</a> </div>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> </a> <a href="#" class="ai">See all products from this category</a> </div>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a> <a href="#" class="ai">See all products from this category</a> </div>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> </a> <a href="#" class="ai">See all products from this category</a> </div>
  <? else :?>
  <div id="product_image" rel="post" module="media/gallery"> 
     <?  $pics = get_media($post['id'], $for = 'content', $media_type = 'picture')  ;
		
		//p($pics);
		?>
  
  
   <? if(!empty($pics['pictures'])): ?>
   <?  $main_pic = $pics['pictures'][0];  ?>
   <? endif; ?>
  
  
  <a class="product product_active" href="#"> <span class="img" style="background-image: url('<? print get_media_thumbnail($main_pic['id'], 250);  ?>')">&nbsp;</span> <strong>
    <? print addslashes($main_pic['media_name']);?>
    </strong> <span class="best_seller">&nbsp;</span> </a>
    
    
    
    
    
    
    <script type="text/javascript">

                    $(document).ready(function(){
                       $("#products_slide .slide_engine li").multiWrap(2, '<ul></ul>');
                        slide.init({
                            elem:"#products_slide",
                            items:"ul",
                            step:3
                        });

                        slide.init({
                            elem:"#related_slide",
                            items:".product_item",
                            step:3
                        });




                        $("#products_slide li a").click(function(){
                            var href = $(this).attr("href");
                            var rel = $(this).attr("rel");

                            $("#product_image .product").attr("href", rel);
                            $("#product_image .product .img").css("backgroundImage", "url(" + href + ")");


                            return false;
                        });
                        $("#product_image a.product").modal("single")

                    });

                    </script>
    <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
    <div class="photoslider" id="products_slide">
      <div class="photoslider_holder">
        <div class="slide_engine">
     
        <? if(!empty($pics['pictures'])): ?>
        
        
        <? foreach($pics['pictures'] as $pic): ?>
        
        <? //  p($pic);
		
		
		?>
        <?  $small_pic = get_media_thumbnail($pic['id'], 80);  ?>
        <?  $big_pic = get_media_thumbnail($pic['id'], 250);  ?>
        
        
                <li> <a href="<?  print $big_pic ?>" rel="<?  print $big_pic ?>" style="background-image: url('<?  print $small_pic ?>')" title="<? print addslashes($pic['media_name']);?>"> </a> </li>
                
                <? endforeach; ?>
        
        
 
 
 <? endif; ?>
 
        </div>
      </div>
      <span class="slide_left product_slide_left">Back</span> <span class="slide_right product_slide_right">More</span> </div>
  </div>
  <div id="product_main">
    <h3 class="title nopadding">
      <editable  post="<? print $post['id'] ?>" field="content_title"><? print $post['content_title'] ?></editable>
    </h3>
    <br />
    <br />
    Description:<br />
    <div class="richtext">
      <editable  post="<? print $post['id'] ?>" field="custom_field_content_description_big">
        <p>5 watt LED flashlight w/Philips LUMILEDS LED lights
          Withstand water pressure up to 100 meters in depth
          Lasts up to 24 hours of continuous use
          Powered by 4 C Alkaline batteries
          Heat dissipating design helps extend the life of the batteries and LED emitter
          One hand operation thumb switch w/lock switch in off position
          Soft rubber over-molding around the handle for slip-free use
          Triple seal soft rubber over-molding prevents water leakage
          Packaged in clamshell </p>
      </editable>
      <br />
      
      
       <mw module="content/custom_fields" post_id="<? print $post['id'] ?>" type="shop">
      
      

      
      Model: LT06 <br />
      Choose color: &nbsp;
      <input type="radio" />
      Yellow
      
      
      
      
      
      
      
      
      <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
      
      
      
      
      <h4 class="title pprice nopadding nomargin">Price: $ <editable  post="<? print $post['id'] ?>" field="custom_field_price">10</editable></h4>
      <div class="borc"> <a href="#" class="buy">Buy now</a>
        <div>OR</div>
        <a href="#" class="con_tag">Contact us</a> </div>
    </div>
  </div>
  <div class="c"></div>
  <br />
  <br />
  <h2 class="title">Similar products</h2>
  <br />
  <div class="photoslider" id="related_slide">
    <div class="photoslider_holder" style="width: 710px">
      <div class="slide_engine">
      
      
      
      <mw module="posts/list" />
      
      
      
      
      
      
      
      
      
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
        <div class="product_item product_item_slide"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>Hood/Vest</strong> <span class="best_seller">&nbsp;</span> </a>
          <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
          <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
      </div>
    </div>
    <span class="slide_left product_slide_left">Back</span> <span class="slide_right product_slide_right">More</span> </div>
  <? //p($post); ?>
  <div class="product_item"> <a class="product" href="#"> <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span> <strong>
    <editable  post="<? print $post['id'] ?>" field="content_title"><? print $post['content_title'] ?></editable>
    </strong> <span class="best_seller">&nbsp;</span> </a>
    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
    <br />
    <a href="#" class="btnH left">Read more</a> <a href="#" class="lbuy right">Buy now</a> </div>
  <? endif; ?>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
