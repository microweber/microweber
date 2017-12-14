<?php

/*

type: layout

name: Cover with Text 2

position: 28

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '2';
}
/* Is Parallax */
$is_parallax = get_option('is_parallax', $params['id']);
if ($is_parallax === null OR $is_parallax === false OR $is_parallax == '') {
    $is_parallax = 'no';
}
?>

<section class="cover height-70 safe-mode <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> imagebg nodrop edit <?php print $padding ?>" field="layout-skin-28-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>political-1.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-6 allow-drop">
                <h1>I'm fighting for a fair go for all Australians</h1>
            </div>
        </div>

    </div>

</section>