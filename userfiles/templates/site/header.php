<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
    
    <title>{content_meta_title}</title>
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

    

    <script type="text/javascript">
        mw.require("<?php print( INCLUDES_URL); ?>js/jquery-1.9.1.js");
    </script>
    <script type="text/javascript">


        mw.require("url.js");
        mw.require("tools.js");
        mw.require("<?php print( INCLUDES_URL); ?>css/mw.ui.css");

    </script>
    <? if(isset($custom_head)): ?>
    <? print $custom_head; ?>
    <? else : ?>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=greek,latin,cyrillic-ext,latin-ext,cyrillic" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.css" type="text/css" media="all">
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap-responsive.css" type="text/css" media="all">


    <link rel="stylesheet" href="{TEMPLATE_URL}css/new_world.css" type="text/css" media="all">
    <script type="text/javascript" src="{TEMPLATE_URL}js/bootstrap.js"></script>
    <script type="text/javascript" src="{TEMPLATE_URL}js/default.js"></script>
    <? endif; ?>
    </head>
    <body>
<div id="header" class="clearfix">
      <div class="container">
        <a href="javascript:;" id="logo" title="Microweber - Make Web">Microweber - Make Web</a>
        <ul id="main-menu" class="nav nav-pills mw-nav">
            <li><a href="javascript:;">Get Started</a></li>
            <li><a href="javascript:;">How to use</a></li>
            <li><a href="javascript:;">Documentation</a></li>
            <li><a href="javascript:;">Contacts</a></li>
        </ul>
      </div>
    </div>
<!-- /#header --> 

