<?php

/*

type: layout

name: Cover with Paragraph

position: 26

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

<section class="imagebg height-90 safe-mode <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-26-<?php print $params['id'] ?>"
         rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero23.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 allow-drop">
                <p class="lead"><?php _lang("Hi, I’m David, I’m a serial entrepreneur turned interactive strategist specializing in digital product development, creative direction, and marketing. My most recent project is Mylo, a men’s style advice app and staples shop.", "templates/dream"); ?></p>
            </div>
        </div>
    </div>
</section>