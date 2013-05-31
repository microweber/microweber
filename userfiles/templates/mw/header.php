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

    

    <script type="text/javascript">
        mw.require("<?php print( INCLUDES_URL); ?>js/jquery-1.9.1.js");
    </script>
    <script type="text/javascript">


        mw.require("url.js");
        mw.require("tools.js");
        mw.require("<?php print( INCLUDES_URL); ?>css/mw.ui.css");

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
  <script type="text/javascript" src="{TEMPLATE_URL}js/jquery-1.10.0.min.js"></script>
  <script type="text/javascript" src="{TEMPLATE_URL}js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{TEMPLATE_URL}js/site.js"></script>
    
    
    <?php endif; ?>
    </head>
    <body>
    <script>if(self !== top) { document.body.className += ' iframe' }</script>

 

    <div id="header">
        <div class="container">
            <a href="http://microweber.com" id="logo" title="Microweber - Make Web - Open Source Drag &amp; Drop Content Management System">"Microweber - Make Web - Open Source Drag &copy; Drop Content Management System</a>

            <div id="download_section">
                <small id="mwversion" title="Version: Beta 0.7024">Beta v. 0.7024</small>
                <a href="javascript:;" class="download-btn">Download</a>
            </div>

            <div id="usernav">
                <a href="javascript:;">Sign up</a>
                <span>or</span>
                <a href="javascript:;">Login</a>
            </div>

              <div id="main-menu-toggle">
                <a class="btn btn-info" onclick="$('#main-nav').toggle();">
                  <span>Menu</span>
                  <span class="icon-align-justify icon-white"></span>
                  <span class="caret"></span>
                </a>
              </div>


              
              <ul class="nav nav-pills" id="main-nav">
                <li class="active"><a href="javascript:;">Home</a></li>
                <li><a href="javascript:;">Features</a></li>
                <li><a href="javascript:;">How to</a></li>
                <li><a href="javascript:;">Comunity</a></li>
                <li><a href="javascript:;">Support</a></li>
              </ul>


        </div>
    </div>
