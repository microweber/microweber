<script type="text/javascript">

function set_gallery_img($new_src, $new_src_big, $title, $color_num){
	
//	$('.zoomWrapperImage img').attr('src', $new_src_big);
	//$('.zoomWrapperImage img').css('z-index',50000);
	//$('.zoomWrapperImage img').load();
	
	 // $(".hoverproduct").remove();
	
  $(".jqZoomWindow").remove();
  $(".jqZoomPup").remove();
  $(".jqzoom").remove();
  
  
  
 
  
  
 
 
  $('#set_gallery_img_z').append('<a href="'+$new_src_big+'" class="cloud-zoom jqzoom" rel="adjustX: 10, adjustY:-4"><img width="250" src="'+$new_src+'" title="'+$title+'" id="main-product-image" /></a>');
 
   $col_n = $('img[cf_img_number="'+$color_num+'"]', '.drop_down_colors_non_selected').attr('src');
   $col_s = $('img[cf_img_number="'+$color_num+'"]' ,'.drop_down_colors_non_selected').attr('description');
    $col_t = $('img[cf_img_number="'+$color_num+'"]', '.drop_down_colors_non_selected').attr('title');
 //  alert($col_n);
   $('span.only_colors img').attr('src', $col_n)
    $('span.only_colors img').attr('title', $col_t);
	 $('span.only_colors img').attr('description', $col_s);
	 
	 
	  $('span.only_colors img').attr('description', $col_s);
   
   
    $('.only_colors input').val( $col_s );
      
	 $('span.only_colors > img:last').remove();
	 
   setTimeout('tw_set_sizes_from_colors()', 50);

  //alert( $col_n);
  
  
  //$('#set_gallery_img').attr('src', $new_src);
	
	//$('#set_gallery_img_big').attr('href', $new_src_big);
  
  
  
  
  
  
  
  
  
  
  
  
  
  
 	make_jq_zoom()
}

function qty_to_price(){
	$q = $('#qty_for_price').val();
	
	$p = $('#products_option_form input[name="custom_field_price"]').val();
	 
	$end = $q * $p;
	
	
	$('#full_price_total').html($end);
	
	
	//alert($end );
	
}


function make_jq_zoom(){
	
   var options1 = {  
          zoomType: 'innerzoom',  
          preloadImages: true,  
            alwaysOn:true,  
            zoomWidth: 250,  
            zoomHeight: 250  
          
         
            
            
            //...MORE OPTIONS  
    };  
	
	$('.cloud-zoom').CloudZoom(); 
    //$('a.jqzoom').jqzoom(options1);  	
}

$(document).ready(function() {
    qty_to_price()

//
//
//				            $('.jqzoom').jqzoom({
//															  lens:true,
//            preloadImages: false,
//					            					zoomType: 'innerzoom'
//					        					});
//				 
//					
					
					
			make_jq_zoom()	
 
});

</script>
<script>
 
 

$(document).ready(function() {


 
 tw_set_sizes_from_colors()
 


 });




$('.each_color').live('click', function() {
setTimeout('tw_set_sizes_from_colors()', 500);

$lic = $(this).attr('color_number');
$pic_nums =  $('[pic_number="'+$lic+'"]').attr('tn_small');
 $pic_numb =  $('[pic_number="'+$lic+'"]').attr('tn_big');
 
 if($pic_nums != undefined){
	  set_gallery_img($pic_nums, $pic_numb);
 }




 

//alert($pic_num);

});
$('.ch_colors').live('click', function() {
$pic_number = $(this).attr('pic_number');

//alert($pic_number);
  var DropItemHTML = $('div[color_number="'+$pic_number+'"]').html();
    var DropItemValue = $('div[color_number="'+$pic_number+'"]').attr("title");
	
//	$(".DropDown").find("input").val(DropItemValue);
	
	
  //  $(".DropDown").find("span").html(DropItemHTML );

	
	

});


function add_to_cart_this(){
	$colorzzzz = $('#products_option_form').find('.custom_field_razmeri_new').size();
	
	
	if($colorzzzz > 0){
			$('.custom_field_razmeri').remove();
	}
	
	 
	mw.cart.add('#products_option_form', function(){add_to_cart_callback()});
}

function tw_set_sizes_from_colors(){

  
  //p( $pics);

$size_to_color = $('span.only_colors img').attr('description');
//alert($size_to_color);
	if($size_to_color != undefined && $size_to_color != ''){
		$new_sizes = $size_to_color.split(',');
		$new_sizes_l = $new_sizes.length;
		$('.custom_field_razmeri_new').remove();
		$str_s = '<select class="custom_field_razmeri_new" name="custom_field_razmeri_new">';
 

		var i=0;
		if($new_sizes_l > 0){
for (i=0;i<=$new_sizes_l;i++)
{
	if($new_sizes[i] != undefined && $new_sizes[i] != 'undefined'){
 $str_s = $str_s+  '<option value="'+$new_sizes[i]+'">'+$new_sizes[i]+'</option>';
	}
}

$str_s = $str_s+ '</select>';
		  
		
	}
	$('.custom_field_razmeri').before($str_s);
	$('.custom_field_razmeri').hide();
	} else {
		$('.custom_field_razmeri').show();
		$('.custom_field_razmeri_new').remove();
	}
	
 



	
}
 
 </script>

<div class="left gallery_box">


<? $pictures = get_pictures($post['id']); ?>
 
<div class="rounded_box transparent" id="set_gallery_img_z"> 
 <a href='<? print get_media_thumbnail( $pictures[0]['id'] , '1200')  ?>' class='cloud-zoom jqzoom' id='zoom1'
            rel="adjustX: 10, adjustY:-4">
            <img src="<? print get_media_thumbnail( $pictures[0]['id'] , '400')  ?>" alt='' width="250" title="<? print addslashes($pictures[0]['media_name']) ?>"   />
        </a>
  <div class="lt"></div>
  <div class="rt"></div>
  <div class="lb"></div>
  <div class="rb"></div>
</div>
<br/>
<div id="gallery">
  <? if(!empty($pictures)): ?>
  <?
  $i = 0;
  foreach($pictures as $pic): ?>
 
  <a class="ch_colors"  pic_number="<? print $i; ?>" tn_small="<? print get_media_thumbnail( $pic['id'] , 400)  ?>" tn_title="<? print addslashes($pic['media_name']) ?>"  tn_big="<? print get_media_thumbnail( $pic['id'] , '1200')  ?>" href="javascript:set_gallery_img('<? print get_media_thumbnail( $pic['id'] , 400)  ?>', '<? print get_media_thumbnail( $pic['id'] , '1200')  ?>', '<? print addslashes($pic['media_name']) ?>', <? print $i; ?>);"><img src="<? print get_media_thumbnail( $pic['id'] , 400)  ?>"  height="120" alt="" /></a>
  <? 
$i++;  endforeach ;  ?>
  <? endif; ?>
</div>


 
  <?
 
/* 
  <div class="rounded_box transparent"> <img id="set_gallery_img" src="<? print get_media_thumbnail( $media ['pictures'][0]['id'] , 250)  ?>" width="250" alt="" />
    <div class="lt"></div>
    <div class="rt"></div>
    <div class="lb"></div>
    <div class="rb"></div>
  </div>
  <br/>
  <div id="gallery">
    <? if(!empty($media["pictures"])): ?>
    <? foreach($media["pictures"] as $pic): ?>
    <a href="javascript:set_gallery_img('<? print get_media_thumbnail( $pic['id'] , 250)  ?>');"><img src="<? print get_media_thumbnail( $pic['id'] , 70)  ?>" alt="" /></a>
    <? endforeach ;  ?>
    <? endif; ?>
    
    */
    ?>
  <!--  
  <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_03.jpg" alt="" /></a> <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_05.jpg" alt="" /></a> <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_07.jpg" alt="" /></a> <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_13.jpg" alt="" /></a> <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_14.jpg" alt="" /></a> <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/gallery/Products_inner_15.jpg" alt="" /></a>
  
  -->
</div>
<div class="left padding_L16 w350">
  <h1 class="title_product pink_color font_size_18"><? print $post['content_title']; ?></h1>
  <div class="products_description"> <? print $post['content_description']; ?> <br />
    <br />
    <? print $post['the_content_body']; ?> <br/>
    <!-- <span class="text_align_right pink_color"><i>Единична цена: <span class="font_size_18">38.00</span> лв.</i></span> -->
    <br />
  </div>
  <br />
  <div class="product_added_holder">
    <h3>Успешно добавихте продукта в количката за пазаруване.</h3>
    <br />
    <br />
    <p> Имате <a href="<? print page_link($shop_page['id']); ?>/view:cart"><strong><span class="items cart_items_qty"><? print get_items_qty() ; ?></span> продукта</strong></a> във вашата кошница. </p>
    <div id="buy_it_box"> <a href="javascript:add_to_cart_callback();" class="rounded pink_color left"> <span class="in1"> <strong><span class="in2 min_w_120">Пазарувай още</span> </strong></span> </a> <a href="<? print page_link($shop_page['id']); ?>/view:cart" class="rounded right pink_btn"> <span class="in1"> <span class="in2 min_w_120">Завършете поръчката </span> </span> </a> </div>
  </div>
  <div class="product_info_holder">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div id="fb-root"></div>
          <script src="http://connect.facebook.net/bg_BG/all.js#appId=156746347726744&amp;xfbml=1&amp;locale=bg_BG"></script>
          <fb:like href="<? print post_link($post['id']);?>" locale="bg_BG" send="false" width="370" show_faces="true" action="recommend" font="arial"></fb:like></td>
      </tr>
    </table>
    <p class="font_size_10"><i>*  За да изберете този продукт  изберете цвят и размер.</i></p>
    <form id="products_option_form" method="post" action="#">
      <input type="hidden" value="<? print $post['id'] ?>"   name="post_id" />
      
      
      
      
      
      
    
      
      
     
      
      
    
      
      <? 

	$p = cf_val($post['id'], 'price');
	
	
	
 
	//$p  = explode('_', $p );
	
if(!empty($p )){
	$p=array_merge(array(),$p);
	 
	if(user_id() != false){
		 $price = $p[1]['value'];
	} else {
		 $price= $p[0]['value'];
	}
	}
	 
 
	?>
    
      
      <? 	//$cf_post =  $this->core_model->getCustomFields('table_content', $post['id'], $return_full = true); 
	
	//p($cf_post);
	?>
    
    
    
    
    
    
    
    
     <table border="0" cellspacing="0" cellpadding="0">
     
     
     
     
     
     
     
     
     
       <tr>
    
    
    
    
    
     <td> 
      Размер:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      <td>
     
      <? $sizes =   cf_val($post['id'], 'sizes');
 
	   ?>
      
      
      <? if(!empty($sizes)): ?>
        <select  name="custom_field_sizes" >
		<? foreach($sizes as $i): ?>
        <option <? if($i['baseline'] =="checked"): ?> selected="selected" <? endif; ?> ><? print  $i['value'] ?></option>
          <?php   endforeach; ?>
         </select>
      <? endif; ?>
       
      </td>
      </tr>
     
     
      
     
     
     
     
       <tr>
    
    
    
    
    
     <td> 
      Цвят:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      <td>
   
 <? $colors =   cf_val($post['id'], 'colors_selector');
 //p($colors );
	   ?>
       <? if(!empty($colors)): ?>
       
        <script language="javascript">
$(document).ready(function(e) {
try {
$(".mydds").msDropDown();
} catch(e) {
alert(e.message);
}
});
</script>

        <select style="width:120px" class="mydds" name="custom_field_color">
     <?php foreach($colors as $color): ?>
      <option value="<?php print base_url() ?>colors/<?php print $color; ?>" title="<?php print base_url() ?>colors/<?php print $color; ?>"><?php print $color; ?></option>
 <?php   endforeach; ?>    </select> 
    
    
       
        <? endif; ?>
      </td>
      </tr>
     
     
    <tr>
    
    
    
    
    
     <td> 
      Цена:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      <td>
   
<input name="custom_field_price" value="<? print  $price   ?>" type="hidden"> <? print $price ?> лв.
 
      </td>
      </tr>
       </table>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      <!--<div class="left">
      <label>Избери Цвят:</label>
      <select>
        <option>цвят 1</option>
        <option>цвят 2</option>
      </select>
    </div>
    
    
    
    
    
    <div class="clener h10"></div>
    <div class="left">
      <label>Избери Размер:</label>
      <select>
        <option>цвят 1</option>
        <option>цвят 2</option>
      </select>
    </div>
    <div class="left">
      <label>Избери Брой:</label>
      <select>
        <option>цвят 1</option>
        <option>цвят 2</option>
      </select>
    </div>-->
      <? // p($cf_post); ?>
      <div class="left custom_field"> <span>Избери Брой:</span>
        <select name="qty" id="qty_for_price" onchange="qty_to_price()">
          <? for ($i = 1; $i <= 50; $i++) { ?>
          <option value="<?  print $i ?>">
          <?  print $i ?>
          </option>
          <? }  ?>
        </select>
      </div>
      <div class="clener"></div>
    </form>
    <div class="full_price">
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td align="center"><span class="pink_color font_size_18"><i>Крайна цена:</i></span></td>
          <td width="" align="center"><div id="full_price_total">
              <microweber module="content/custom_fields" content_id="<? print $post['id'] ?>" only_type="price" only_value="1"  module_id="custom_field_price_product<? print $page['id'] ?>" />
            </div></td>
          <td align="left"><div class="full_price_total"> <?php print option_get('shop_currency_sign') ; ?> </div></td>
        </tr>
      </table>
    </div>
    <br />
    <div class="custom_field"> <a href="<? print TEMPLATE_URL ?>size_charts/tabl2.gif" class="pink_color" target="_blank"><img src="http://www.dinodirect.com/Templates/Site61/Dino/images/buyingguide/ad1.gif" height="20" align="left" hspace="5" />Таблица с размери</a> </div>
    <div id="buy_it_box"> <i>* Всички цени са в български лева</i> <a href="javascript: add_to_cart_this()" class="rounded right pink_btn"> <span class="in1"> <span class="in2 min_w_120">Купи сега</span> </span> </a> </div>
  </div>
</div>
<div class="clener"></div>
<div class="pattern_line margin_30-0-18-0"></div>
<h2 class="title_h40 pr"> Подобни продукти <span id="pager"></span> <span class="slide-btn_box" id="next"></span> <span class="slide-btn_box" id="prev"></span> </h2>
<div id="related_products">
  <? $cats =CATEGORY_IDS;

$cats = explode(',',$cats);

$last_cat = end($cats);
//p($last_cat);

?>
  <? if(!empty($cats )) : ?>
  <? $last_cat = end($cats);

$related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 1; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 
 
 
 $related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 2; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 
 
 
 
 $related_posts_params = array(); 
   //params for the output
   //$related_posts_params['display'] = 'post_item.php';
   //params for the posts
    $related_posts_params['selected_categories'] = array($last_cat); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$related_posts_params['items_per_page'] = 3; //limits the results by paging
	$related_posts_params['curent_page'] = 3; //curent result page
	//$related_posts_params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
$related_posts = get_posts($related_posts_params);
$posts_data = $related_posts['posts'];
 include  TEMPLATE_DIR."layouts/shop/items_list.php"; 


//p($related_posts );
?>
  <? endif;  ?>
  <? 
 
 
 
 

 


 
   
 ?>
</div>
