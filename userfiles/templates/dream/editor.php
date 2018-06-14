<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Live edit</title>
    <script>mw.lib.require('bootstrap3');</script>
        <?php $color_scheme = get_option('color-scheme', 'mw-template-dream'); ?>
        <?php
        if (!$color_scheme) {
                $color_scheme = '';
        } else {
                $color_scheme = '-' . $color_scheme;
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
    <link href="{TEMPLATE_URL}assets/css/mw-dream.css" rel="stylesheet" type="text/css" media="all"/>
    <link href='https://fonts.googleapis.com/css?family=Lora:400,400italic,700%7CMontserrat:400,700' rel='stylesheet' type='text/css'>



</head>
<body>
{content}
</body>
</html>