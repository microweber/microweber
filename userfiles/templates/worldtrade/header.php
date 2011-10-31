<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>{content_meta_title}</title>


<meta NAME="Description" CONTENT="{content_meta_description}">


<link href="<? print TEMPLATE_URL ?>css/site.css" rel="stylesheet" type="text/css" />
<? echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->'; ?>
<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery.cycle.all.min.js"></script>





<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/site.js"></script>
  
 
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/anything_zoomer/js/zoomer.jquery.js"></script>

 
 
 
 
<link href="<? print TEMPLATE_URL ?>js/cloudzoom/cloud-zoom.css" rel="stylesheet" type="text/css" />

 
 
<script type="text/JavaScript" src="<? print TEMPLATE_URL ?>js/cloudzoom/cloud-zoom.1.0.2.min.js"></script>
 

<script type="text/javascript">
         function add_to_cart_callback(){
			 
			 $(".product_added_holder").toggle();
			 $(".product_info_holder").toggle();
			 
		 
			 
			 
			 

         /*   var html1 = ""
            + "<h2 style='padding:20px 20px 35px 20px;text-align:center'>Item Successfully Added</h2>"

            +"<div style='text-align:center'>"
            +'<a class="btn js_generated" href="<? print site_url('products/view:checkout'); ?>"><span class="b_left">&nbsp;</span><span class="b_mid"><span class="">Checkout</span></span><span class="b_right">&nbsp;</span></a>'
            +"<div class='c' style='padding-bottom:8px;'>&nbsp;</div>"
            +"<a href='javascript:void(0)' onclick='Modal.close()' style='color: #D89E07;font-weight: normal;'>Continue Shopping</a>"
            +"</div>";


			Modal.box(html1, 400, 150);
            Modal.overlay();*/




			 
		 }
         
         
         </script>
</head>
<body onload="set_min_height()">
<div id="main">
<div id="main-2">
<div id="content" class="wrapper mw">
<div id="header">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="<? print TEMPLATE_URL ?>images/header.jpg" alt="" /></td>
      <td><? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
        <? if(!empty($shop_page)): ?>
        <a href="<? print page_link($shop_page['id']); ?>/view:cart" style="padding-left:10px;"> <span class="items pink_color bold cart_items_qty"><? print get_items_qty() ; ?></span> артикула <span class="pink_color bold">виж</span></a>   
        <? endif; ?></td>
    </tr>
  </table>
  <div id="top_nav">
    <microweber module="content/menu"  name="main_menu"  />
  </div>
  <!--  <ul id="top_nav">
          <li><a href="#">Начало</a></li>
          <li><a href="#" class="active">Продукти</a></li>
          <li><a href="#">Услуги</a></li>
          <li><a href="#">За Нас</a></li>
          <li><a href="#">Новини</a></li>
          <li><a href="#">За контакти</a></li>
        </ul>-->
  <form id="search" action="<? print page_link($shop_page['id']); ?>" method="post">
    <div class="rounded white_btn right">
      <div class="in1">
        <input class="in2" type="submit"  value="Търси" />
      </div>
    </div>
    <div class="rounded input right">
      <div class="in1">
        <div class="in2">
          <input type="text" name="search_by_keyword" />
        </div>
      </div>
    </div>
    <div class="clener"></div>
  </form>
</div>
