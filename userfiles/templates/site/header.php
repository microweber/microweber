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
    <?php if(isset($custom_head)): ?>
    <?php print $custom_head; ?>
    <?php else : ?>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=greek,latin,cyrillic-ext,latin-ext,cyrillic" />
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap.css" type="text/css" media="all">
    <link rel="stylesheet" href="{TEMPLATE_URL}css/bootstrap-responsive.css" type="text/css" media="all">
    <link rel="stylesheet" href="{TEMPLATE_URL}css/new_world.css" type="text/css" media="all">
    <script type="text/javascript" src="{TEMPLATE_URL}js/bootstrap.js"></script>
    <script type="text/javascript" src="{TEMPLATE_URL}js/default.js"></script>
    <?php endif; ?>
    </head>
    <body>
<div id="header" class="clearfix">
      <div class="container"> <a href="<?php print site_url('home') ?>" id="logo" title="Microweber - Make Web">Microweber - Make Web</a>
    <ul id="main-menu" class="nav nav-pills mw-nav">
          <li><a href="<?php print site_url('home') ?>#home-video">Download</a></li>
<!--          <li><a href="<?php print site_url('home') ?>#how-to-use">How to use</a></li>
-->


<!--          <li><a href="javascript:;" id="doc-popup">How to install</a></li>
-->          

          <li><a href="<?php print site_url('developers-help') ?>">How to...</a></li>


<li><a href="<?php print site_url('home') ?>#contact-us">Contacts</a></li>
        </ul>
    <div id="doc-modal" class="hide">
          <div align="left">
        <h4>We are currently writing our documentation.</h4>
        <div >
              <div class="clearfix post-comments"> <b class="">Microweber is free &amp; open source content management&nbsp;system or CMS. <br class="">
                </b><br class="">
            When you&nbsp;want to install Microweber for a first time, you must know few things.</div>
              <div class="clearfix post-comments">
            <p>The following <strong>server requirements</strong> are needed:</p>
            <ul>
                  <li>Apache web server</li>
                  <li>PHP 5.3 or above</li>
                  <li>MySQL 5 or above</li>
                  <li>short_open_tag must be set to "on" in php.ini
              <li>mod_rewrite must be enabled</li>
                  </li>
                </ul>
     
          </div>
              <div class="clearfix post-comments">
               <font size="6" class="">.htaccess setup</font>
               
               <p>If you are installing MW in subfolder on your webserver you must edit your .htaccess file and set your folder name with the <em>RewriteBase</em> parameter<br class="">
                  
                </p>
              
              
                <font size="6" class="">Database Setup</font>
              
               <font size="6" class=""><img src="/MW_SETUP_1.jpg" class="element element-image" id="image_1367135275115"><br class="">
                </font><br class="">
                <p>When you start with the install you will need set admin account. This will be the only way to manage your website, blog or online shop. The fields you will need to fill are:<br class="">

                  
                </p>
            <ul class="" style="font-weight: bold;">
                  <li class=""> <b class="" style="line-height: 1.5;">MySQL hostname&nbsp;</b><br class="">
              </li>
                </ul>
            This is the place where your Website database will be hosted. For example if you make&nbsp;installation&nbsp;on your personal computer the name will be (<i class=""><b class="">localhost</b></i>), but if you already downloaded Microweber (MW) on your own server you must type (localhost) or the name of the database&nbsp;server host (ex. mysql.mywebsite.com).&nbsp; <br class="">
          </div>
              <div class="clearfix post-comments">
            <ul class="">
                  <li class=""> <b class="" style="line-height: 1.5;">MySQL username </b><br class="">
              </li>
                </ul>
            On this field you must type the username you will use as a login for your database</div>
              <div id="row_1366889599063" style="width:95%" class="element">
            <p align="justify" class=""></p>
            <ul class="">
                  <li class=""> <b class="" style="line-height: 1.5;">MySQL password</b><br class="">
              </li>
                </ul>
            <span class="" style="line-height: 20px;">Type your password for the database user<br class="">
                </span><br class="">
            <ul class="">
                  <li class=""> <b class="" style="line-height: 1.5;">Database name</b><br class="">
              </li>
                </ul>
            <span class="" style="line-height: 1.5;">The name of your database. You must have prevoulsy created this database in the MySQL server</span><span class="" style="line-height: 1.5;">.<br class="">
                <br class="">
                </span>
            <div class="" id="el_1366890782206">
                  <p class=""></p>
                  <ul class="">
                <li class=""> <b class="" style="line-height: 1.5;">Table prefix</b><br class="">
                    </li>
              </ul>
                  <span class="" style="line-height: 20px;">If you want to have multiple&nbsp;installations&nbsp;in one database you can set a custom table prefix. The name of this prefix it is up to you for example you can name it (</span><i class="" style="line-height: 20px;">site_1</i><span class="" style="line-height: 20px;">) or&nbsp;something&nbsp;else.<br class="">
              <br class="">
              <font size="6" class="">Admin user setup</font></span>
              <p class=""></p>
                  <p class=""><span class="" style="line-height: 20px;"><font size="6" class=""><b class=""><br class="">
              </b></font></span></p>
                  <p class=""></p>
                  <ul class="">
                <li class=""> <b class=""><font size="3" class="">Admin username</font></b><br class="">
                    </li>
              </ul>
                  Here on this field you need to choose the username for your Microweber CMS. This name will be needed&nbsp;everytime&nbsp;when you want to login to manage your website or online shop. Good practice will be to escape the "admin"&nbsp;abbreviature but it is up to you. For example I use  my name or the name of my cat. <br class="">
              <br class="">
              <ul class="">
                <li class=""><span class="" style="line-height: 24px;"><b class="">Admin email</b></span></li>
              </ul>
                  Use real email for this. When you forgot your password you will need it!
              <p class=""></p>
                  <p class=""><br class="">
              </p>
                  <div class="" id="el_1366890790175">
                <p class=""></p>
                <ul class="">
                  <li class=""><b class="" style="line-height: 1.5;">Admin Password</b></li>
                    </ul>
                <div class=""><span class="" style="line-height: 20px;">Please set strong and secure password. How strong is your password is equal to how  much time will take someone break it and get your website.</span></div>
                <p class=""></p>
              </div>
                  Click the <font size="6" class="">Install </font>Button and Make Web with Microweber. <br>
              <br>
                </div>
          </div>
            </div>
      </div>
        </div>
  </div>
    </div>
<!-- /#header --> 

