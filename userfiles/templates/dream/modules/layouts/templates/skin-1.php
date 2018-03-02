<?php

/*

type: layout

name: Cover Media 1

position: 1

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '6';
}
?>
<script>
    mw.lib.require('fitty');
</script>
<script>

    $(document).ready(function () {
        var el = document.getElementById('fitty-<?php print $params['id'] ?>');
        $('#fitty-<?php print $params['id'] ?>').removeAttr('style');
        if (el) {
            fitty(el);
        }
    });

    $(document).ready(function () {
        var $window = $(window);
        var windowWidth = $window.width();
        var windowHeight = $window.height();
        var navHeight = $('nav').outerHeight(true);

        // Disable parallax on mobile

        if ((/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i).test(navigator.userAgent || navigator.vendor || window.opera)) {
            $('section').removeClass('parallax');
        }

        if (windowWidth > 768) {
            var parallaxHero = $('.parallax:nth-of-type(1)'),
                parallaxHeroImage = $('.parallax:nth-of-type(1) .background-image-holder');

            parallaxHeroImage.css('top', -(navHeight));
            if (parallaxHero.outerHeight(true) == windowHeight) {
                parallaxHeroImage.css('height', windowHeight + navHeight);
            }
        }
    });
</script>

<section class="height-100 imagebg cover cover-1 <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit safe-mode <?php print $padding ?>" data-overlay="<?php print $overlay; ?>"
         field="layout-skin-1-<?php print $params['id'] ?>"
         rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero1.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3 text-center">
                <div class="allow-drop">
                    <p class="logo-text"><span id="fitty-<?php print $params['id'] ?>" class="safe-element"><?php _lang("Dream", "templates/dream"); ?>.</span></p>
                </div>
            </div>
            <div class="col-xs-12 text-center">
                <div class="allow-drop">
                    <p class="lead" style="top: 0;">
                        <?php print _lang('A beautiful collection of hand-crafted web components', 'templates/dream'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="modal-instance modal-video-1">
<!--                    <div class="edit allow-drop" field="layout-skin-1-btn---><?php //print $params['id'] ?><!--" rel="module">-->
                        <div class="element"><module type="btn" text="<?php _lang("Watch video", "templates/dream"); ?>" /></div>
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 pos-absolute pos-bottom">
        <div class="row">
            <div class="col-sm-12 text-center">
                <module type="social_links" id="socials"/>
            </div>
        </div>
    </div>
</section>