<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
    <title>{content_meta_title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{content_meta_title}"/>
    <meta name="keywords" content="{content_meta_keywords}"/>
    <meta name="description" content="{content_meta_description}"/>
    <meta property="og:type" content="{og_type}"/>
    <meta property="og:url" content="{content_url}"/>
    <meta property="og:image" content="{content_image}"/>
    <meta property="og:description" content="{og_description}"/>
    <meta property="og:site_name" content="{og_site_name}"/>

    <link rel="alternate" type="application/rss+xml" title="{og_site_name}" href="<?php print site_url('rss') ?>"/>


    <link href='//fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Slab:400,300&subset=latin,cyrillic,cyrillic-ext,greek,latin-ext' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300italic&subset=latin,cyrillic,greek,latin-ext' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        mw.lib.require("bootstrap3");
        mw.require(mw.settings.template_url + "js/functions.js");
    </script>
    <link rel="stylesheet" href="<?php print template_url(); ?>css/style.css" type="text/css"/>
    <?php $color_scheme2 = get_option('color-scheme2', 'mw-template-liteness'); ?>
    <?php if ($color_scheme2 != '' AND $color_scheme2 != 'lite'): ?>
        <link href="{TEMPLATE_URL}css/color_scheme/<?php print $color_scheme2; ?>.css" rel="stylesheet" type="text/css" id="color_scheme2"/>
    <?php endif; ?>
    <?php include THIS_TEMPLATE_DIR . 'header_options.php'; ?>


    <script>
        AddToCartModalContent = window.AddToCartModalContent || function (title) {
                var html = ''

                    + '<section style="text-align: center;">'
                    + '<h5>' + title + '</h5>'
                    + '<p><?php _e("has been added to your cart"); ?></p>'
                    + '<p><a href="javascript:;" onclick="mw.tools.modal.remove(\'#AddToCartModal\')" class="btn btn-default"><?php _e("Continue shopping"); ?></a>'
                    + '<a href="<?php print checkout_url(); ?>" class="btn btn-warning"><?php _e("Checkout"); ?></a></p></section>';

                return html;
            }
    </script>


</head>
<body class="<?php print $font . ' ' . $bgimage; ?> <?php print helper_body_classes(); ?>">


<div id="a1"></div>
<br><br><br><br>
<div id="a2"></div>
<br><br><br><br>
<script>

mw.require('autocomplete.js');
mw.require('tags.js');
    $(document).ready(function(){
        new mw.autoComplete({
            element:'#a1',
            map:{title:'title', value:'id'},
            data:[
                {title:"lorem", image:'https://picsum.photos/100/100', id:1} ,
                {title:"ipsum", image:'https://picsum.photos/100/100', id:2} ,
                {title:"sit amet", image:'https://picsum.photos/100/100', id:3} ,
            ],
            selected:[{title:"asdas ddsasaasd", image:'https://picsum.photos/100/100', id:2}]
        })
         new mw.autoComplete({
            element:'#a2',
            map:{title:'title', value:'id'},
            multiple:true,
            data:[
                {title:"lorem", image:'https://picsum.photos/100/100', id:1} ,
                {title:"ipsum", image:'https://picsum.photos/100/100', id:2} ,
                {title:"sit amet", image:'https://picsum.photos/100/100', id:3} ,
            ],
            selected:[{title:"asdas ddsasaasd", image:'https://picsum.photos/100/100', id:2}]
        })
    })

</script>

<div id="main-container">
    <div id="header">
        <div class="container edit" field="liteness-header" rel="global">
            <div class="row">
                <div class="col-md-9">
                    <h1 class="edit element" id="logo" field="logo-top" rel="global">
                        <a href="<?php print site_url(); ?>">
                            <span>Liteness</span>
                            <small>Define your own Space</small>
                        </a>
                    </h1>
                </div>
                <div class="col-md-3">
                    <div class="header-cart">
                        <module type="shop/cart" template="small">
                    </div>
                </div>
            </div>
        </div>
        <div id="main-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <module type="menu"/>
                    </div>
                    <div class="col-md-3" id="header-search">
                        <module type="search" template="autocomplete"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content-holder">