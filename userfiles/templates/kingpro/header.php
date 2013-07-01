<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
    <title>{content_meta_title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?php print site_url('sitemap.xml'); ?>" />

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
        mw.require("<?php print( INCLUDES_URL ); ?>css/mw.ui.css");

    </script>
    <?php if(isset($custom_head)): ?>
    <?php print $custom_head; ?>
    <?php else : ?>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Serif:400,500,700,400italic,700italic" type="text/css" media="all" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap-responsive.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/font-awesome/css/font-awesome.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/style.responsive.css" type="text/css" media="all" />
    <script src="{TEMPLATE_URL}js/functions.js" type="text/javascript"></script>


    <?php endif; ?>
    </head>
    <body>


    <div id="header" class="edit">

        <div id="top">
            <div class="container"><module type="shop/cart" template="minimal"></div>
        </div>

        <div id="header-main">
            <div class="container">
                <div class="mw-row">
                    <div class="mw-col" style="width: 20%">
                       <div class="element">
                          <a href="<?php print site_url(); ?>"><img src="{TEMPLATE_URL}img/logo.png" alt="" /></a>
                       </div>
                    </div>
                    <div class="mw-col" style="width: 50%">
                        <module type="menu" template="minimal">
                    </div>
                    <div class="mw-col" style="width: 30%">
                         <module type="search" template="autocomplete">
                    </div>
                </div>



            </div>
        </div>

    </div> <!-- /#header -->

    <div id="content">


