<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
    <title>{content_meta_title}</title>
    <link href="{TEMPLATE_URL}img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{content_meta_title}">
    <meta name="keywords" content="{content_meta_keywords}">
    <meta name="description" content="{content_meta_description}">
    <meta property="og:type" content="{og_type}">
    <meta property="og:url" content="{content_url}">
    <meta property="og:image" content="{content_image}">
    <meta property="og:description" content="{og_description}">
    <meta property="og:site_name" content="{og_site_name}">



    <script type="text/javascript" src="{TEMPLATE_URL}js/jquery-1.10.0.min.js"></script>

    <script type="text/javascript">

        mw.require("url.js");
        mw.require("tools.js");
        mw.require("<?php print( MW_INCLUDES_URL); ?>css/mw.ui.css");

    </script>

    <?php if(isset($custom_head)): ?>
    <?php print $custom_head; ?>
    <?php else : ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=greek,latin,cyrillic-ext,latin-ext,cyrillic">
  <link rel="stylesheet" type="text/css" href="{TEMPLATE_URL}css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="{TEMPLATE_URL}css/bootstrap-responsive.css" />
  <link rel="stylesheet" type="text/css" href="{TEMPLATE_URL}css/style.css" />
  <link rel="stylesheet" type="text/css" href="{TEMPLATE_URL}css/style.responsive.css" />

  <script type="text/javascript" src="{TEMPLATE_URL}js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{TEMPLATE_URL}js/site.js"></script>


    <?php endif; ?>
    </head>
    <body>