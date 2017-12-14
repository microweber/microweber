<?php

/*

type: layout

name: Icon Features 7

position: 66

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '3';
}
?>

<section class="features features-8 imagebg height-100 <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop safe-mode edit <?php print $padding ?>" data-overlay="<?php print $overlay; ?>"
         field="layout-skin-66-<?php print $params['id'] ?>" rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero15.jpg');"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Bold, Simple,
                    <em>Robust.</em>
                </h2>
                <p class="lead">
                    Pillar features a bevvy of content blocks and layouts to create your website.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="boxed boxed--lg voh">
                    <div class="feature col-sm-6">
                        <i class="icon icon--lg icon-Windows-2 safe-element"></i>
                        <h5>Lovingly Crafted Components</h5>
                        <p class="lead allow-drop">
                            Tailored for modern startups, portfolios shops and more.
                        </p>
                    </div>
                    <div class="feature col-sm-6">
                        <i class="icon icon--lg icon-Medal safe-element"></i>
                        <h5>Highly Regarded Author</h5>
                        <p class="lead allow-drop">
                            Awarded by Envato for providing high quality products.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>