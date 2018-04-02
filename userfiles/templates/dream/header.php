<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
    <title>{content_meta_title}</title>
    <meta charset="utf-8"/>
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/><![endif]-->
    <meta property="og:title" content="{content_meta_title}"/>
    <meta name="keywords" content="{content_meta_keywords}"/>
    <meta name="description" content="{content_meta_description}"/>
    <meta property="og:type" content="{og_type}"/>
    <meta property="og:url" content="{content_url}"/>
    <meta property="og:image" content="{content_image}"/>
    <meta property="og:description" content="{og_description}"/>
    <meta property="og:site_name" content="{og_site_name}"/>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>
    <script>
        mw.lib.require('bootstrap3');
        mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');
    </script>
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">-->

    <script>
        AddToCartModalContent = window.AddToCartModalContent || function (title) {
                var html = ''
                    + '<section style="text-align: center;">'
                    + '<h5>' + title + '</h5>'
                    + '<p><?php _e("has been added to your cart"); ?></p>'
                    + '<a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="btn btn-default"><?php _e("Continue shopping"); ?></a> &nbsp;'
                    + '<a href="<?php print checkout_url(); ?>" class="btn btn-warning"><?php _e("Checkout"); ?></a></section>';
                return html;
            }

        mw.on('mw.cart.add', function () {
            var notification = $('body').find('.notification[data-notification-link="cart-overview"]');
            notification.removeClass('notification--dismissed');
            mr.notifications.showNotification(notification, 0);
            return false;
        });
    </script>

    <?php $color_scheme = get_option('color-scheme', 'mw-template-dream'); ?>
    <?php
    if (!$color_scheme) {
        $color_scheme = '';
    } else {
        $color_scheme = '-' . $color_scheme;
    }
    ?>
    <?php
    $stop_transparent_nav = get_option('stop_transparent_nav', 'mw-template-dream');
    if ($stop_transparent_nav == '') {
        $stop_transparent_nav = 'false';
    }
    ?>

    <link href="{TEMPLATE_URL}assets/css/socicon.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/iconsmind.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/interface-icons.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/owl.carousel.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/lightbox.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/theme.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/theme<?php print $color_scheme; ?>.css" id="theme-color" rel="stylesheet" type="text/css" media="all"/>
    <link href="{TEMPLATE_URL}assets/css/custom.css" rel="stylesheet" type="text/css" media="all"/>
    <link href='https://fonts.googleapis.com/css?family=Lora:400,400italic,700%7CMontserrat:400,700' rel='stylesheet' type='text/css'>

    <?php if ($stop_transparent_nav != 'true'): ?>
        <script>
            function checkFirstSectionForNav() {
                var firstSectionHas = $('.main-container section').first().hasClass('imagebg');

                var skip = $('.main-container section').first().hasClass('background-image-holder');
//d(skip);
//d($('.main-container section').first());
                if (!skip && firstSectionHas == true) {
                    $('nav .nav-bar').addClass('nav--absolute nav--transparent');
                } else {
                    $('nav .nav-bar').removeClass('nav--absolute nav--transparent');
                }
            }

            $(document).ready(function () {
                checkFirstSectionForNav();

                $(window).on('moduleLoaded', function () {
                    checkFirstSectionForNav();
                });
            });
        </script>
    <?php endif; ?>
    <script>
     $(window).on('load', function () {
       if(mw.iconSelector){
          mw.iconSelector.addCSS('link[href*="/iconsmind.css"]', '.icon-')
       }
     });
    </script>
</head>
<body>
<a id="top"></a>

<?php $shopping_cart = get_option('shopping-cart', 'mw-template-dream'); ?>
<?php $search_field = get_option('search-field', 'mw-template-dream'); ?>
<?php $profile_link = get_option('profile-link', 'mw-template-dream'); ?>
<?php $preloader = get_option('preloader', 'mw-template-dream'); ?>
<?php $shop1_header_style = get_option('shop1-header-style', 'mw-template-dream'); ?>
<?php $shop2_header_style = get_option('shop2-header-style', 'mw-template-dream'); ?>

<?php if ($preloader != '' AND $preloader == 'true'): ?>
    <div class="loader"></div>
<?php endif; ?>


<nav class="<?php if ($preloader != '' AND $preloader == 'true'): ?>transition--fade<?php endif; ?>">
    <div class="nav-bar" data-fixed-at="200">
        <div class="nav-module logo-module left">
            <module type="logo" id="logo" template="default" default-text="Dream"/>
        </div>

        <module type="menu" name="header_menu" class="nav-module menu-module left" template="header"/>

        <!--end nav module-->
        <?php if ($shopping_cart == 'true'): ?>
            <div class="nav-module right cart-module">
                <a href="#" class="nav-function" data-notification-link="cart-overview">
                    <i class="interface-bag icon icon--sm"></i>
                    <span><?php _e("Cart") ?></span>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($profile_link == 'true'): ?>
            <div class="nav-module right cart-module">
                <a href="<?php print profile_url(); ?>" class="nav-function">
                    <i class="fa fa-user"></i>
                    <span>Profile</span>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($search_field == 'true'): ?>
            <div class="nav-module right search-module">
                <a href="#" class="nav-function modal-trigger" data-modal-id="search-form">
                    <i class="interface-search icon icon--sm"></i>
                    <span><?php _e("Search") ?></span>
                </a>
            </div>
        <?php endif; ?>

        <div class="nav-module right hidden-xs hidden-sm hidden-md safe-mode">
            <span class="nav-function phone-header edit safe-element" rel="global" field="dream_header_phone" style="">
                <?php _lang("Call us", "templates/dream"); ?>: +1 555 666
            </span>
        </div>
    </div>
    <!--end nav bar-->
    <div class="nav-mobile-toggle visible-sm visible-xs">
        <i class="icon-Align-Right icon icon--sm"></i>
    </div>
</nav>

<?php if ($shopping_cart == 'true'): ?>
    <div class="notification pos-right pos-top cart-overview" data-notification-link="cart-overview" data-animation="from-right">
        <module type="shop/cart" template="small"/>
    </div>
<?php endif; ?>

<?php if ($search_field == 'true'): ?>
    <div class="modal-container search-modal" data-modal-id="search-form">
        <div class="modal-content bg-white imagebg" data-width="100%" data-height="100%">
            <div class="pos-vertical-center clearfix">
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 text-center">
                    <form class="clearfix" action="<?php print site_url(); ?>search.php" method="get">
                        <div class="input-with-icon">
                            <i class="icon-Magnifi-Glass2 icon icon--sm"></i>
                            <input type="search" name="keywords" placeholder="<?php _lang("Type your search and hit enter", "templates/dream"); ?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end of modal-content-->
    </div>
<?php endif; ?>

<div class="main-container <?php if ($preloader != '' AND $preloader == 'true'): ?>transition--fade<?php endif; ?>">

