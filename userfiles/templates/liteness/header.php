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
    <link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,300&subset=latin,cyrillic,cyrillic-ext,greek,latin-ext' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic&subset=latin,cyrillic,greek,latin-ext' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        mw.lib.require("bootstrap3");
        mw.require(mw.settings.template_url + "js/functions.js");
    </script>
    <link rel="stylesheet" href="<?php print template_url(); ?>css/style.css" type="text/css" />
    <?php
        $color_scheme = get_option('color-scheme', 'mw-template-liteness');
        if($color_scheme==''){
          $color_scheme = 'default';
        }
        $font = get_option('font', 'mw-template-liteness');
        $bgimage = get_option('bgimage', 'mw-template-liteness');
    ?>
    <link rel="stylesheet" id="colorscss" href="<?php print template_url(); ?>css/colors/<?php print $color_scheme; ?>.css" type="text/css" />
    <style type="text/css" id="customscheme"></style>
</head>
<body class="<?php print $font . ' ' . $bgimage; ?>">
<div id="main-container">
    <div id="header">
       <div class="container">
        <div class="row">
            <div class="col-md-9">
              <h1 class="edit nodrop element" id="logo" field="logo-top" rel="global">
                  <a href="<?php print site_url(); ?>">
                    <span>Liteness</span>
                    <small>Define your own Space</small>
                  </a>
               </h1>
            </div>
            <div class="col-md-3">
              <div class="edit header-cart" field="header-cart" rel="global"><module type="shop/cart" template="small"></div>
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
    <div id="content-holder">