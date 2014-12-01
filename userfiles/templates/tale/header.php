<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#" class="tr-coretext tr-aa-subpixel">
<head>
    <title>{content_meta_title}</title>
    <script src="http://typerendering.com/trmix.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:title" content="{content_meta_title}">
    <meta name="keywords" content="{content_meta_keywords}">
    <meta name="description" content="{content_meta_description}">
    <meta property="og:type" content="{og_type}">
    <meta property="og:url" content="{content_url}">
    <meta property="og:image" content="{content_image}">
    <meta property="og:description" content="{og_description}">
    <meta property="og:site_name" content="{og_site_name}">
    <link href='//fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,300&subset=latin,cyrillic,cyrillic-ext,greek,latin-ext' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic&subset=latin,cyrillic,greek,latin-ext' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        mw.lib.require("bootstrap3");
        mw.require(mw.settings.template_url + "js/functions.js");
    </script>
    <link rel="stylesheet" href="<?php print template_url(); ?>css/style.css" type="text/css" />
    <?php include THIS_TEMPLATE_DIR . 'header_options.php'; ?>


    <style>
    .module-products-template-columns-3 .valign, .module-posts-template-columns .valign, .module-posts-template-columns .valign *, .module-products-template-columns-3 .valign *{
      height: 160px;
    }

    hr{
      max-width: 150px;
      margin: 25px auto;
    }

    .quote{
      max-width: 658px;
      line-height: 24px;
      text-align: center;
      margin: auto;
      padding-left: 30px;

    }
    .quote h2{
        padding-bottom: 20px;

    }

    .quote p{
        font-family: Georgia;
      font-style: italic;
      font-size: 14px;
      text-align: justify;
    }

    .lead{
      font-size: 16px;
    }


    body,
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6, textarea{ font-family: "Lato", serif; }




    </style>

</head>
<body class="<?php print $font . ' ' . $bgimage; ?>">
<div id="main-container">
    <div id="header-master">
        <div class="container">
            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col" style="width: 200px;">
                    <div class="mw-ui-col-container">
                        <module type="logo" id="site-logo">
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <span id="mobile-menu"><span></span><span></span><span></span><span></span></span>
                        <div id="main-menu">
                             <module type="menu" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="header">
        <div id="header-container">
           <div class="container edit" field="dream-header" rel="global">
               <div class="header-cart pull-right"><?php /*<module type="shop/cart" template="small">*/ ?></div>
           </div>
        </div>
          <div id="bgimage" style="background-image: url(<?php print template_url(); ?>img/home.jpg);">
              <div id="bgimagemaster">
                <div id="bgimagecontent">
                    <h1 class="edit element" id="master-header" field="logo-top" rel="global">
                        <a href="<?php print site_url(); ?>">
                          <span>Pana Travel</span>
                          <small>Start your trip today</small>
                        </a>
                    </h1>
                </div>
              </div>
          </div>
          <script>
            $(window).bind('load scroll', function(){
               var $bgim = $("#bgimage");
               $bgim.css("backgroundPosition", "0 " + $(window).scrollTop()/2.5 +"px");
            });
          </script>
    </div>
    <div id="content-holder">