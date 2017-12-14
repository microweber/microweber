<?php

/*

type: layout

name: Cover With Form

position: 24

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '4';
}
?>

<section class="imagebg safe-mode height-70 cover cover-7 <?php if($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-24-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>video.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Start a conversation.</h2>
                <p class="lead">
                    Browse and connect with employers from all over the world.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center-xs">
                <module type="search" template="skin-1"/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="inline-element">
                    <span class="h6 safe-element" style="line-height: 0px;">How Does Dream Work?</span>
                </div>
                <div class="inline-element">
                    <module type="btn" text="Purchase Dream" template="skin-1"/>
                </div>
            </div>
        </div>
    </div>
</section>