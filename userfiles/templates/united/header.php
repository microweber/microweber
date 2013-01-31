<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="<?php print( INCLUDES_URL); ?>js/jquery.js"></script>

<!-- Meta Information -->
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">
<meta property="og:title" content="{content_meta_title}" />
<link rel="stylesheet" href="{THIS_TEMPLATE_URL}bootstrap.css" type="text/css" media="screen">
</head>
<body>
<div class="header-block clearfix"> 
  <!-- Navigation -->
  <header>
    <div class="container">
      <div class="row">
        <div class="span12 clearfix">
          <div class="navbar navbar_ clearfix">
            <div class="navbar-inner">
              <div class="container">
                <h1 class="brand"><a href="index.html"></a></h1>
                <select id = "responsive-main-nav-menu" onchange = "javascript:window.location.replace(this.value);">
                </select>
                <div id="main-nav-menu" class="nav-collapse nav-collapse_ collapse">
                  <module type="nav" name="header_menu" id="main_nav" ul_class="nav sf-menu" li_class="sub-menu" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</div>
