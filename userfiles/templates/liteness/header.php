<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
    <title>{content_meta_title}</title>
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
    <script type="text/javascript">
        mw.lib.require("bootstrap3");
    </script>
    <link rel="stylesheet" href="<?php print template_url(); ?>css/style.css" type="text/css" />
</head>
<body>
<div id="main-container">
    <div id="header">
       <div class="container">
        <div class="row">
            <div class="col-md-9">
              <h1 class="edit nodrop element" id="logo">
                  <a href="<?php print site_url(); ?>">
                    <span>Liteness</span>
                    <small>Define your own Space</small>
                  </a>
               </h1>
            </div>
            <div class="col-md-3">
              <div class="header-cart"><module type="shop/cart" template="small"></div>
            </div>
       </div>
       </div>
       <div id="main-menu">
            <div class="container">
              <div class="row">
                  <div class="col-md-9"><module type="menu" /></div>
                  <div class="col-md-3" id="header-search"><module type="search" template="autocomplete" /></div>
              </div>
            </div>
       </div>
    </div>