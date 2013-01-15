<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">
mw.require("<?php print( INCLUDES_URL); ?>js/jquery.js");
</script>

<!-- Meta Information -->
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">
<meta property="og:title" content="{content_meta_title}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />
<link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.css" type="text/css" media="screen">
<link rel="stylesheet" href="{TEMPLATE_URL}css/responsive.css" type="text/css" media="screen">
<link rel="stylesheet" href="{TEMPLATE_URL}css/style.css" type="text/css" media="screen">
<link rel="stylesheet" href="{TEMPLATE_URL}css/camera.css" type="text/css" media="screen">
<link rel="stylesheet" href="{TEMPLATE_URL}css/portfolio.css" type="text/css" media="screen">
<link rel="stylesheet" href="{TEMPLATE_URL}css/elements.css" type="text/css" media="screen">
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
                <div id = "main-nav-menu" class="nav-collapse nav-collapse_ collapse">
                <module type="nav" name="header_menu" ul_class="nav sf-menu" />
                  <ul class="nav sf-menu">
                    <li class="active sub-menu"><a href="index.html"><span>Home <em> welcome page</em></span></a><em></em>
                      <ul>
                        <li><a href="index.html">Home 1</a><em></em></li>
                        <li><a href="index2.html">Home 2</a></li>
                        <li><a href="index3.html">Home 3</a></li>
                        <li><a href="index4.html">Home Portfolio</a></li>
                        <li class="sub-menu"><a href="#">Template Colors</a>
                          <ul>
                            <li><a href="http://www.owltemplates.com/demo/website/decision/blue/index.html">Blue</a><em></em></li>
                            <li><a href="index.html">Green</a></li>
                            <li><a href="http://www.owltemplates.com/demo/website/decision/yellow/index.html">Yellow</a></li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                    <li class="sub-menu"><a href="#"><span>Layouts <em> what we do</em></span></a><em></em>
                      <ul>
                        <li><a href="about.html">About us</a><em></em></li>
                        <li><a href="service.html">Services</a></li>
                        <li><a href="blog.html">Blog</a></li>
                        <li><a href="single-blog.html">Single Post</a></li>
                        <li class="sub-menu"><a href="#">Portfolio</a>
                          <ul>
                            <li><a href="portfolio2.html">2 Columns</a><em></em></li>
                            <li><a href="portfolio3.html">3 Columns</a></li>
                            <li><a href="portfolio4.html">4 Columns</a></li>
                            <li><a href="portfolio6.html">6 Columns</a></li>
                            <li><a href="portfolio1.html">Single Project</a></li>
                          </ul>
                        </li>
                        <li><a href="price.html">Pricing Tables</a></li>
                        <li><a href="sidebar-right.html">Page - Sidebar Right</a></li>
                        <li><a href="sidebar-left.html">Page - Sidebar Left</a></li>
                        <li><a href="row-2.html">Two Rows</a></li>
                        <li><a href="sitemap.html">Sitemap</a></li>
                        <li><a href="404.html">404 Page not found</a></li>
                      </ul>
                    </li>
                    <li class="sub-menu"><a href="#"><span>Elements <em> site elements</em></span></a><em></em>
                      <ul>
                        <li><a href="element.html">Element Page</a><em></em></li>
                        <li class="sub-menu"><a href="#">Bootstrap Page</a>
                          <ul>
                            <li><a href="scaffolding.html">Scaffolding</a><em></em></li>
                            <li><a href="base-css.html">Base CSS</a></li>
                            <li><a href="components.html">Components</a></li>
                            <li><a href="javaScript.html">JavaScript</a></li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                    <li><a href="contact.html"><span>Contact us <em> stay in touch</em></span></a><em></em></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</div>
