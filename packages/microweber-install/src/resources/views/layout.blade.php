<!DOCTYPE HTML>
<html>
<head>
    <title><?php _e('Installation'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" Content="en">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>
    <?php print mw_header_scripts() ?>


    <link type="text/css" rel="stylesheet" media="all" href="<?php print asset('vendor/microweber-packages/microweber-filament-theme/microweber-filament-theme.css'); ?>"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print asset('vendor/microweber-packages/frontend-assets/build/install.css'); ?>"/>

</head>
<body>


<main class="w-100 h-100vh ">

    @hasSection('content')
        @yield('content')
    @endif

</main>





<div id="dialog-message-marketplace" title="Marketplace items" style="display: none">

</div>
<?php print mw_footer_scripts() ?>


</body>
</html>
