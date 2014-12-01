<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
    <head>
        <title>{content_meta_title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <!--  Site Meta Data  -->
        <meta name="keywords" content="{content_meta_keywords}">
        <meta name="description" content="{content_meta_description}">

        <!--  Site Open Graph Meta Data  -->
        <meta property="og:title" content="{content_meta_title}">
        <meta property="og:type" content="{og_type}">
        <meta property="og:url" content="{content_url}">
        <meta property="og:image" content="{content_image}">
        <meta property="og:description" content="{og_description}">
        <meta property="og:site_name" content="{og_site_name}">

        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300,600,700' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" type="text/css" href="<?php print mw_includes_url(); ?>css/ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php print TEMPLATE_URL; ?>css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php print TEMPLATE_URL; ?>css/colors.css" />
        <script>
            AddToCartModalContent = window.AddToCartModalContent || function(title){
                var html = ''
                + '<section>'
                  + '<span class="sm-icon-bag2"></span>'
                  + '<h5>' + title + '</h5>'
                  + '<p><?php _e("has been added to your cart"); ?></p>'
                  + '<a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="mw-ui-btn"><?php _e("Continue shopping"); ?></a>'
                  + '<a href="<?php print checkout_url(); ?>" class="mw-ui-btn mw-ui-btn-invert"><?php _e("Checkout"); ?></a></section>';
                return html;
            }
        </script>
        <script>mw.require('<?php print TEMPLATE_URL; ?>js/functions.js', true);</script>

    </head><?php

      $template_settings = get_option('shopmagdata', 'mw-template-shopmag');
      $template_settings = json_decode($template_settings, true);

      $demo = false;

   ?><body class="<?php print(implode(' ', $template_settings));  ?>">


   <?php if($demo == true){ ?>

    <div class="previewctrl" style="position: fixed;top:120px;left:20px;z-index:99999999;cursor: default">

        <div class="mw-ui-box">
          <div class="mw-ui-box-header">
            <span class="mw-icon-template"></span><span>Template Options</span></div>
            <div class="mw-ui-box-content" style="background-color: white">
                <label class="mw-ui-label">Layout type</label>
                <div class="mw-ui-btn-nav">
                    <a class="mw-ui-btn active" onclick="$(document.body).removeClass('fixed');$(this).addClass('active');$(this).next().removeClass('active')">Fluid</a>
                    <a class="mw-ui-btn" onclick="$(document.body).addClass('fixed');$(this).addClass('active');$(this).prev().removeClass('active')">Fixed</a>
                </div>
                <label class="mw-ui-label">Colors</label>
               <script>
               smsetcolor = function(el){
                mw.tools.classNamespaceDelete(mwd.body, 'shopmag-');
                mw.tools.addClass(mwd.body, $(el).dataset('val'));
               }
               mw.require('jquery-ui.js');
               $(document).ready(function(){
                $(".previewctrl").draggable({containment:'window'});
               })
               smfontselect = function(name){
                        mw.tools.classNamespaceDelete(parent.mwd.body, 'smfont-');
                        mw.tools.addClass(parent.mwd.body, name);
                   }
               </script>
                <style>

                .template-color{
                  display: inline-block;
                  width: 20px;
                  height: 20px;
                  cursor: pointer;
                  border: 1px solid #ddd;
                  box-shadow: inset 1px 1px 0 #fff, inset -1px -1px 0 #fff;
                  margin:5px;
                }

                </style>

                <span class="template-color" data-val="" onclick="smsetcolor(this)" style="background-color:#000;"></span>
                <span class="template-color" data-val="shopmag-red" onclick="smsetcolor(this)" style="background-color:#DB0743;"></span>
                <span class="template-color" data-val="shopmag-purple" onclick="smsetcolor(this)" style="background-color:#C27AC1;"></span> <br>
                <span class="template-color" data-val="shopmag-orange" onclick="smsetcolor(this)" style="background-color:#EE9A00;"></span>
                <span class="template-color" data-val="shopmag-blue" onclick="smsetcolor(this)" style="background-color:#0092DB;"></span>
                <span class="template-color" data-val="shopmag-violet" onclick="smsetcolor(this)" style="background-color:#CD6889;"></span>
                <hr>
                <label class="mw-ui-label">Font</label>
                <select class="mw-ui-field" id="smfontselect" onchange="smfontselect(this.value)">
                    <option value="">Source Sans Pro (Default)</option>
                    <option value="smfont-arial">Arial</option>
                    <option value="smfont-georgia">Georgia</option>
                    <option value="smfont-exo">Exo 2</option>
                </select>
            </div>
        </div>

    </div>   <?php  } ?>

    <div id="wrapper">

    <div id="header-holder">
      <div id="header">
          <div class="mw-wrapper">
              <div class="mw-ui-row-nodrop" id="header-row">
                <div class="mw-ui-col" id="logocolumn">
                  <div class="mw-ui-col-container">
                    <module type="logo" id="logo" />
                  </div>
                </div>
                <div class="mw-ui-col" id="menucolumn">
                  <div class="mw-ui-col-container">
                    <div class="header-menu">
                        <span class="mw-icon-menu menu-button"></span>
                        <module type="menu" template="default" id="header-navigation">
                    </div>
                  </div>
                </div>
                <div class="mw-ui-col sm-header-tools" id="headertoolscolumn">
                  <div class="mw-ui-col-container">
                    <div class="header-shopcarts-holder">
                      <module type="shop/cart" template="small" class="header-shopcart no-settings">
                      <div class="mw-dopdown-cart">
                        <module type="shop/cart" template="dropdown" class="no-settings">
                      </div>
                    </div>
                    <?php if(is_logged() == false and get_option('enable_user_registration') == 'y'){ ?>
                      <a href="<?php print login_url(); ?>" style="color:white;margin: 25px 20px 0 0;float: right;">Sign In</a>
                    <?php } ?>
                    <?php if(is_logged() == true){ ?>
                        <div class="mw-dropdown mw-dropdown-sm user-dropdown" data-readonly="true">
                          <span class="mw-dropdown-value mw-ui-btn mw-dropdown-button mw-dropdown-val mw-ui-btn-icon"><span class="sm-icon-user"></span></span>
                          <div class="mw-dropdown-content" style="display: none;">
                            <ul>
                              <li ><a href="<?php print site_url(); ?>profile/#section=profile">Profile</a></li>
                              <li ><a href="<?php print site_url(); ?>profile/#section=orders">My orders</a></li>
                              <li ><a href="<?php print logout_url(); ?>">Logout</a></li>
                            </ul>
                          </div>
                        </div>
                    <?php } ?>
                    <module type="search" template="minimal" id="header-search">
                  </div>
                </div>
              </div>
          </div>
      </div><!-- /#header -->
    </div><!-- /#header-holder -->