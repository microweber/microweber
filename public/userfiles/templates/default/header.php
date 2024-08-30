<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
    <title>{content_meta_title}</title>


    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{content_meta_title}">
    <meta name="keywords" content="{content_meta_keywords}">
    <meta name="description" content="{content_meta_description}">
    <meta property="og:type" content="{og_type}">
    <meta property="og:url" content="{content_url}">
    <meta property="og:image" content="{content_image}">
    <meta property="og:description" content="{og_description}">
    <meta property="og:site_name" content="{og_site_name}">

	<?php if($page and isset($page['content_type']) and $page['content_type']=='product'){ ?>
	<script type="application/ld+json">
	  { "@context":"http://schema.org/","@type":"Product","sku":"{product_sku}","image":"{content_image}","name":"{content_meta_title}","description":"{content_meta_description}","offers":{ "@type": "Offer","priceCurrency":"{product_currency}","price":"{product_price}" } }
	</script>
	<?php } ?>

    <script type="text/javascript">

        mw.require("<?php print( mw_includes_url()); ?>css/ui.css");
        mw.lib.require("bootstrap3");
    </script>


    <?php if(isset($custom_head)): ?>
    <?php print $custom_head; ?>
    <?php else : ?>
    <link rel="stylesheet" href="{DEFAULT_TEMPLATE_URL}css/style.css" type="text/css" media="all">
    <script src="{DEFAULT_TEMPLATE_URL}js/default.js"></script>
    <?php endif; ?>


    </head>
    <body>
<div id="header" class="clearfix">
      <div class="container">
        <div class="edit" rel="global" field="header1">
          <div class="mw-row">
              <div class="mw-col" style="width: 20%">
                  <div class="mw-col-container">
                      <div class="brand element" id="logo"><a href="<?php print site_url(); ?>">Basic Theme</a></div>
                  </div>
              </div>
              <div class="mw-col" style="width: 45%">
                  <div class="mw-col-container">
                      <module type="menu" name="header_menu" id="main-navigation" template="pills"  />
                  </div>
              </div>
              <div class="mw-col" style="width: 35%">
                  <div class="mw-col-container">
                      <module type="shop/cart" template="small" />
                  </div>
              </div>
          </div>
        </div>
  </div>
    </div>


