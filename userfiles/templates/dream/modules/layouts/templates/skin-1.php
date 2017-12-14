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

    $(document).ready(function () {
        var el = document.getElementById('fitty-<?php print $params['id'] ?>');
        if (el && el.length > 0) {
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
            <div class="col-sm-6 text-right text-center-xs">
                <div class="allow-drop">
                    <p class="logo-text"><span id="fitty-<?php print $params['id'] ?>" class="safe-element">Dream.</span></p>
                </div>
            </div>
            <div class="col-sm-6 text-center-xs">
                <div class="allow-drop">
                    <p class="lead">
                        A beautiful collection of
                        <br/> hand-crafted web components
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="modal-instance modal-video-1">
                    <module type="btn" text="Watch video"/>
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