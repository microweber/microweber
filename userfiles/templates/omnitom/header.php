<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="googlebot" content="index,follow" />
<meta name="robots" content="index,follow" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="rating" content="GENERAL" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="start" href="<?php print site_url(); ?>" />
<link rel="home" type="text/html" href="<?php print site_url(); ?>"  />
<link rel="index" type="text/html" href="<?php print site_url(); ?>" />
<meta name="generator" content="Microweber" />
<title>{content_meta_title}</title>
<meta name="keywords" content="{content_meta_keywords}" />
<meta name="description" content="{content_meta_description}" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print site_url('main/rss'); ?>" />
<link rel="sitemap" type="application/rss+xml" title="Sitemap" href="<?php print site_url('main/sitemaps'); ?>" />
<meta name="reply-to" content="<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<link rev="made" href="mailto:<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<meta name="author" content="http://ooyes.net | Mass Media Group Ltd" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<script type="text/javascript">



            imgurl = "<?php print TEMPLATE_URL; ?>img/";



        </script>
<?php include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
<?php if($page['content_section_name'] != 'shop'): ?>
<style type="text/css">
#sub_right {
}
</style>
<?php endif;  ?>
</head>
<body>
<?php if(empty($post)): ?>
<?php 



//var_dump($active_categories);
//$cat_pic = $this->core_model->mediaGetThumbnailForItem('table_taxonomy', $active_categories[1], $size = 'original', $order_direction = "DESC", $media_type = 'picture', $do_not_return_default_image = true); 

$cat_pic = ROOTPATH.'/category_backgrounds/'.$active_categories[1].'.jpg'; 

if($active_categories[1] != false){

//var_dump($cat1);
}
//var_dump($cat_pic);
if(is_file($cat_pic)) : ?>
<style type="text/css">	
	body {
background:url("<?php print pathToURL($cat_pic); ?>");

background-repeat:no-repeat;

background-position:center center;
	}
</style>

<?php else: ?>
	
 <?   
 $somecss = $cat_pic = ROOTPATH.'/category_backgrounds/site.css'; 
 if(is_file($somecss )) : ?>
    <link rel="stylesheet" href="<?php print pathToURL($somecss); ?>" type="text/css" media="screen"  />
 


<?php endif; ?>


<?php endif; ?>
<?php else: ?>
<?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $post['id']);
	$post['custom_fields'] = $more;
	?>
<?php if($post['custom_fields']['background'] !=  '') : 

$cat_pic = ROOTPATH.'/category_backgrounds/'.$post['custom_fields']['background'];; 


//var_dump($cat_pic);
if(is_file($cat_pic)) : ?>
<style type="text/css">	
	body {
background:url("<?php print pathToURL($cat_pic); ?>");

background-repeat:no-repeat;

background-position:center center;



}

</style>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<div id="container">
<div id="container_bg">
  <!--  -->
</div>
<div id="wrapper">
<div id="header" class="wrap"> <a id="logo" href="<?php print site_url(); ?>">Omnitom - High on Earth</a>
  <div id="header_cart">
    <p><strong id="the_cart_qty"></strong> items in your shopping bag</p>
    <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(44); ?>" id="pcdtr1">view bag</a> | <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(45); ?>">checkout</a> </div>
  <div class="clear"></div>
  <div id="navigation-bar" class="wrap">
    <ul>
      <li class="parent"><a href="#" class="parent">OMNITOM</a>
        <ul>
          <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_menu');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
        </ul>
      </li>
      <!--<li class="parent" > <a  href="<?php print $link = CI::model ( 'content' )->getContentURLById(31)  ?>" class="parent<?php if($page['id'] == 31): ?> active<?php endif; ?>">COLLECTIONS</a> </li>-->
      <li class="parent <?php if($page['id'] == 14): ?> active<?php endif; ?>"><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>" class="parent"> ONLINE BOUTIQUE</a>
        <ul>
          <li><a href="<?php print site_url('collections'); ?>">Shop by collections</a></li>
          <li><a href="<?php print site_url('shop'); ?>">Shop by items</a></li>
          <li><a href="<?php print site_url('sale'); ?>">Special Offers</a></li>
          <li><a href="<?php print site_url('shop/categories:460'); ?>">Accessories</a></li>
          <? /*
          <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('on_line_shop_menu');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
*/ ?>
        </ul>
      </li>
      <li class="parent <?php if($page['id'] == 194): ?> active<?php endif; ?>"><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(194); ?>" class="parent"> COLLECTIONS</a>
        <? $colls = CI::model ( 'taxonomy' )->getChildrensRecursive(2060, 'category');

	//  p($colls);
	  ?>
        <ul>
          <?php foreach($colls as $item):	 $item1 = CI::model ( 'taxonomy' )->getSingleItem($item);
	 ?>
          <?php if($item != 2060): ?>
          <li><a  name="<?php print addslashes( $item1['taxonomy_value'] ) ?>" href="<?php print CI::model ( 'taxonomy' )->getUrlForIdAndCache($item) ?>"><?php print ( $item1['taxonomy_value'] ) ?></a></li>
          <?php endif; ?>
          <?php endforeach ;  ?>
        </ul>
      </li>
      <li class="parent"><a href="#" class="parent">OMNITOM WORLD</a>
        <ul>
          <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_world');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
        </ul>
      </li>
      <li class="parent"><a href="#" class="parent">GET IN TOUCH</a>
        <ul>
          <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('get_in_touch');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
        </ul>
      </li>
    </ul>
    <form method="post" action="<?php print site_url(); ?>search" id="searchbar">
      <div class="box">
        <input type="text" value="<?php print $this->core_model->getParamFromURL ( 'keyword' ) ?>" class="blurfocus" name="searchsite" />
      </div>
      <a href="javascript:;" class="small_btn" onclick="$('#searchbar').submit();"><span>GO</span></a>
    </form>
  </div>
  <!-- /navigation-bar -->
</div>
<!-- /#header -->
<div id="content" class="wrap">
<div id="subheader" class="wrap">
  <h2 class="title" title="<?php print ucwords (addslashes($page['content_title'])) ?>"><?php print ucwords($page['content_title']) ?></h2>
  <?php $quick_nav = CI::model ( 'content' )->getBreadcrumbsByURLAndPrintThem();  ?>
  <div id="sub_right"> <span class="sort-by">Currency: </span>
    <div class="DropDown DropDownB sort_by_drop" id="sort_by_drop"> <span></span>
      <!--[if IE 6]><div id="sort_by_drop_ie_bg"></div><![endif]-->
      <ul>
        <li <?php if($this->session->userdata ( 'shop_currency' ) == 'EUR' ): ?> class="active" <?php endif; ?> title="EUR" onclick="change_shop_currency('EUR')">EUR</li>
        <li  <?php if($this->session->userdata ( 'shop_currency' ) == 'USD' ): ?> class="active" <?php endif; ?>  title="USD" onclick="change_shop_currency('USD')">USD</li>
        <li  <?php if($this->session->userdata ( 'shop_currency' ) == 'GBP' ): ?> class="active" <?php endif; ?>  title="GBP" onclick="change_shop_currency('GBP')">GBP</li>
      </ul>
      <input type="hidden"  name="shop_currency"  id="shop_currency_celector"  value="<?php print $this->session->userdata ( 'shop_currency' ); ?>"   />
    </div>
  </div>
  <!-- /sub_right -->
  <? if(($page['id'] == 14  ) or ($page['id'] == 194  )):  ?>
  <?   $promo_page = CI::model ( 'content' )->contentGetByIdAndCache(192);  ?>
<!-- <div class="promo1">
    <div class="promo_title"><? print $promo_page['content_title'] ?></div>
    <div class="promo_desc"><? print html_entity_decode($promo_page['content_description']) ?></div>
    <div class="promo_body"><? print html_entity_decode($promo_page['content_body']) ?></div>
  </div> 
  <?   $promo_page = CI::model ( 'content' )->contentGetByIdAndCache(193);  ?> -->
  <div class="promo2">
    <div class="promo_title"><? print $promo_page['content_title'] ?></div>
    <div class="promo_desc"><? print html_entity_decode($promo_page['content_description']) ?></div>
    <div class="promo_body"><? print html_entity_decode($promo_page['content_body']) ?></div>
  </div>
  <?php endif; ?>
</div>
<!-- /#subheader -->
<?php //var_dump($post); ?>
<?php if($include_ictures_in_the_heeader == true):



				  //$include_pictures_in_the_heeader is defined in the layout file



				   ?>
<?php $pictures = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($page['id'], 'original'); 



				  $pictures2 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($page['id'], '600'); 



				  $pictures3 = CI::model ( 'content' )->contentGetPicturesFromGalleryForContentId($page['id'], '500'); 



				   ?>
<?php if(!empty($pictures)): ?>
<div id="slides" class="V2">
  <div id="slider">
    <?php $i =0; foreach($pictures as $pic): ?>
    <a href="<?php print $pictures2[$i]; ?>" class="box active zoom"



        style="background-image:url('<?php print $pictures3[$i]; ?>')"></a>
    <?php $i++; endforeach; ?>
  </div>
</div>
<span id="slides_left">Back</span> <span id="slides_right">Forward</span>
<div class="clear"></div>
<?php endif; ?>
<?php endif; ?>
<div <?php if($no_class_richtext_in_content == false ): ?>class="richtext" <?php endif; ?>>
<div class="content_body_text"> <?php print html_entity_decode($page['content_body']); ?> </div>
{content} 