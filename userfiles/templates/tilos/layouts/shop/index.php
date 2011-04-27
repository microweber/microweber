<?php

/*

type: layout

name: shop layout

description: shop site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<? include TEMPLATE_DIR."sidebar.php"; ?>

<? $view = url_param('view'); ?>
<?  if($view == 'cart'):  ?>

<div id="main">
  <? include  "cart.php"; ?>
</div>
<?  elseif($view == 'checkout'):  ?>

  <? include  "checkout.php"; ?>

<? else: ?>




<div id="main">




  <? if(empty($post)): ?>
  <? if($posts): ?>
  <? foreach($posts as $post): ?> <? $cf = get_custom_fields($post['id']); 
	
//	p($cf);
	?>
  <div class="product_item"> <a class="product" href="<? print post_link($post['id']) ?>"> <span class="img" style="background-image: url('<? print thumbnail($post['id'], 120);  ?>')">&nbsp;</span> <strong>
   <? print $post['content_title'] ?>
    </strong> 
    
   
    <? if($cf['is_featured']['custom_field_value'] == 'y'): ?>
    <span class="best_seller">&nbsp;</span>
    <? endif; ?>
    
    
     </a> <a href="<? print post_link($post['id']) ?>" class="ai"> See product </a> </div>
  <? endforeach; ?>
  <mw module="content/paging" />
  <? else :?>
  <editable  page="<? print $page['id'] ?>" field="no_posts">No products found.</editable>
  <? endif; ?>
  <? else :?>
   <? $cf = get_custom_fields($post['id']); 
	
//	p($cf);
	?>
    
  <div id="product_image" rel="post" module="media/gallery">
    <?  $pics = get_media($post['id'], $for = 'content', $media_type = 'picture')  ;
		
		//p($pics);
		?>
    <? if(!empty($pics['pictures'])): ?>
    <?  $main_pic = $pics['pictures'][0];  ?>
    <? endif; ?>


    <a class="product product_active" href="<? print get_media_thumbnail($main_pic['id'], 450);  ?>">

    <span class="img" style="background-image: url('<? print get_media_thumbnail($main_pic['id'], 120);  ?>')">&nbsp;</span>


    <strong> <? print addslashes($main_pic['media_name']);?> </strong>
    
    
    
     <? if($cf['is_featured']['custom_field_value'] == 'y'): ?>
    <span class="best_seller">&nbsp;</span>
    <? endif; ?>
    
    </a>
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
          <?  $big_pic2 = get_media_thumbnail($pic['id'], 450);  ?>
          <li> <a href="<?  print $big_pic ?>" rel="<?  print $big_pic2 ?>" style="background-image: url('<?  print $small_pic ?>')" title="<? print addslashes($pic['media_name']);?>"> </a> </li>
          <? endforeach; ?>
          <? endif; ?>
        </div>
      </div>
      <span class="slide_left product_slide_left">Back</span> <span class="slide_right product_slide_right">More</span> </div>
  </div>
  <div id="product_main">
    <form id="add_to_cart_product_<? print $post['id'] ?>">
      <input type="hidden" value="<? print $post['id'] ?>"   name="post_id" />
      <h3 class="title nopadding">
        <editable  post="<? print $post['id'] ?>" field="content_title"><? print $post['content_title'] ?></editable>
      </h3>
      <br />
      <br />
      Description:<br />
      <div class="richtext">
        <editable  post="<? print $post['id'] ?>" field="content_body">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </editable>
        <br />
        <mw module="content/custom_fields" post_id="<? print $post['id'] ?>" type="shop">
        <!--
      
      Model: LT06 <br />
      Choose color: &nbsp;
      <input type="radio" />
      Yellow-->
        <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
        <h4 class="title pprice nopadding nomargin">Price: $
          <editable  post="<? print $post['id'] ?>" field="custom_field_price">enter price</editable>
        </h4>
        <div class="borc"> <a href="#" onclick="mw.cart.add('#add_to_cart_product_<? print $post['id'] ?>', function(){add_to_cart_callback()});" class="buy">Buy now</a>
          <div>OR</div>
          <a href="<? print page_link_to_layout('contacts'); ?>" class="con_tag">Contact us</a> </div>
      </div>
    </form>
  </div>
  <div class="c"></div>
  <br />
  <br />
  <editable  page="<? print $page['id'] ?>" field="simmilar_posts_title">
    <h2 class="title">Simmilar products</h2>
  </editable>
  <br />
  <div class="photoslider" id="related_slide">
    <div class="photoslider_holder" style="width: 710px">
      <div class="slide_engine">
        <mw module="posts/list" file="product_list_item" />
      </div>
    </div>
    <span class="slide_left product_slide_left">Back</span> <span class="slide_right product_slide_right">More</span> </div>
  <? //p($post); ?>
  
  
  
  <editable  page="<? print $page['id'] ?>" field="products_bottom">
 <p>drag here</p>
  </editable>
  <? endif; ?>
</div>
<? endif; ?>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
