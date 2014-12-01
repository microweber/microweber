<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
    <title>{content_meta_title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta property="og:title" content="{content_meta_title}" />
    <meta name="keywords" content="{content_meta_keywords}" />
    <meta name="description" content="{content_meta_description}" />
    <meta property="og:type" content="{og_type}" />
    <meta property="og:url" content="{content_url}" />
    <meta property="og:image" content="{content_image}" />
    <meta property="og:description" content="{og_description}" />
    <meta property="og:site_name" content="{og_site_name}" />
    
    <link rel="alternate" type="application/rss+xml" title="{og_site_name}" href="<?php print site_url('rss') ?>" />


    <link href='//fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,300&subset=latin,cyrillic,cyrillic-ext,greek,latin-ext' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic&subset=latin,cyrillic,greek,latin-ext' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        mw.lib.require("bootstrap3");
        mw.require(mw.settings.template_url + "js/functions.js");
    </script>
    <link rel="stylesheet" href="<?php print template_url(); ?>css/style.css" type="text/css" />
    <?php include THIS_TEMPLATE_DIR . 'header_options.php'; ?>
</head>
<body class="<?php print $font . ' ' . $bgimage; ?>">
<div id="main-container">
    <div id="header">
       <div id="header-overlay"></div>
       <div class="container edit" field="liteness-header" rel="global">
            <div class="text-center ">
              <h1 class="edit element" id="logo" field="logo-top" rel="global">
                  <a href="<?php print site_url(); ?>">
                    <span>SOPHISTIKA</span>
                    <small>So sophisticated template</small>
                  </a>
              </h1>
            </div>
       </div>
       <a href="#content-holder" id="go-to-content-anchor" class="mw-icon-app-arrow-down"></a>
       <div id="main-menu">
            <div class="container">
                <div class="header-cart" style="float: right"><module type="shop/cart" template="small"></div>
                <span class="mobilemenuctrl"><span></span><span></span><span></span><span></span></span><module type="menu" id="header-main-menu" />

            </div>
       </div>
    </div>
    <div id="content-holder"> 